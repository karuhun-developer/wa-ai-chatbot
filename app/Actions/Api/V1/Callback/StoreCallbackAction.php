<?php

namespace App\Actions\Api\V1\Callback;

use App\Enums\Wuz\EventType;
use App\Jobs\Wuz\HandleAiReplyJob;
use App\Models\Wuz\CallbackLog;
use App\Models\Wuz\Device;
use App\Models\Wuz\DeviceContact;
use App\Models\Wuz\DeviceMessage;
use App\Services\WuzService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreCallbackAction
{
    public function __construct(
        public WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(string $token, array $data, ?string $ipAddress = null, ?string $userAgent = null): bool
    {
        $device = Device::where('token', $token)->first();

        // Return false if device not found
        if (! $device) {
            return false;
        }

        // Detect event type
        $eventType = EventType::detect($data);

        // Log the callback
        CallbackLog::create([
            'device_id' => $device->id,
            'event_type' => $eventType->value,
            'payload' => $data,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        // Handle different event types
        match ($eventType) {
            EventType::MESSAGE => $this->handleMessage($device, $data),
            // EventType::DISCONNECTED => $this->handleDisconnected($device),
            // EventType::LOGGED_OUT => $this->handleLoggedOut($device),
            default => null,
        };

        return true;
    }

    /**
     * Handle incoming message events
     */
    private function handleMessage(Device $device, array $data): void
    {
        $info = $data['event']['Info'] ?? [];
        $message = $data['event']['Message'] ?? [];
        $messageId = $info['ID'] ?? null;

        // Extract message details
        $chatLid = $info['Chat'] ?? null;
        $senderJid = $info['SenderAlt'] ?? null;

        // Check if sender jid contains @lid replace with Sender
        if ($senderJid && str_contains($senderJid, '@lid')) {
            $senderJid = $info['Sender'] ?? $senderJid;
        }

        $messageType = 'text';
        $messageContent = null;
        $metadata = [];

        // Webhook
        $webhook = $device->webhooks()->where('event', 'All')->first();

        // Detect message type and extract content
        if (isset($message['conversation'])) {
            $messageType = 'text';
            $messageContent = $message['conversation'];
            $webhook = $device->webhooks()->where('event', 'MessageReceived')->first() ?? $webhook;
        } elseif (isset($message['extendedTextMessage'])) {
            $messageType = 'text';
            $messageContent = $message['extendedTextMessage']['text'] ?? null;
            $webhook = $device->webhooks()->where('event', 'MessageReceived')->first() ?? $webhook;
        } elseif (isset($message['imageMessage'])) {
            $messageType = 'image';
            $messageContent = $message['imageMessage']['caption'] ?? null;
            $metadata = $this->downloadMedia($device, $message['imageMessage'], 'image');
            $webhook = $device->webhooks()->where('event', 'MessageReceived')->first() ?? $webhook;
        } elseif (isset($message['videoMessage'])) {
            $messageType = 'video';
            $messageContent = $message['videoMessage']['caption'] ?? null;
            $metadata = $this->downloadMedia($device, $message['videoMessage'], 'video');
            $webhook = $device->webhooks()->where('event', 'MessageReceived')->first() ?? $webhook;
        } elseif (isset($message['documentMessage'])) {
            $messageType = 'document';
            $messageContent = $message['documentMessage']['fileName'] ?? $message['documentMessage']['title'] ?? null;
            $metadata = $this->downloadMedia($device, $message['documentMessage'], 'document');
            $webhook = $device->webhooks()->where('event', 'MessageReceived')->first() ?? $webhook;
        }

        // Auto Save contact info
        try {
            $contact = DeviceContact::updateOrCreate(
                [
                    'device_id' => $device->id,
                    'phone' => jidToPhone($senderJid),
                ],
                [
                    'push_name' => $info['PushName'] ?? null,
                    'phone_jid' => $senderJid,
                ],
            );

            // Store message in database
            DeviceMessage::create([
                'device_id' => $device->id,
                'device_contact_id' => $contact->id,
                'chat_jid' => $chatLid,
                'sender_jid' => $senderJid,
                'message' => $messageContent,
                'metadata' => $metadata,
                'type' => $messageType,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save contact or message: '.$e->getMessage());
        }

        // Auto mark as read
        $isFromMe = $info['IsFromMe'] ?? false;
        if (! $isFromMe && $messageId && $chatLid && $senderJid && $device->auto_read) {
            try {
                $this->wuzService = new WuzService(userToken: $device->token);
                $this->wuzService->markMessageAsRead(
                    messageId: $messageId,
                    chatPhone: jidToPhone($chatLid),
                    senderPhone: jidToPhone($senderJid),
                );
            } catch (\Exception $e) {
                Log::error('Failed to auto mark message as read: '.$e->getMessage());
            }
        }

        // Send webhook if configured
        if ($webhook && $messageContent) {
            try {
                // Jid to phone number conversion
                $phone = jidToPhone($senderJid);

                Http::post($webhook->url, [
                    'event' => 'MessageReceived',
                    'key' => $device->token,
                    'message' => $message,
                    'sender' => $phone,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send webhook: '.$e->getMessage());
            }
        }

        // Auto reply with AI agent if enabled
        if ($device->ai_enabled && $messageType === 'text' && $messageContent && $senderJid) {
            // Dispatch handle AI reply to a queue to avoid blocking the request
            dispatch(
                new HandleAiReplyJob(
                    device: $device,
                    contact: $contact,
                    chatLid: $chatLid,
                    phone: jidToPhone($senderJid),
                    messageContent: $messageContent,
                )
            );
        }
    }

    /**
     * Download media from WhatsApp
     */
    private function downloadMedia(Device $device, array $mediaMessage, string $type): array
    {
        try {
            $url = $mediaMessage['url'] ?? null;
            $directPath = $mediaMessage['directPath'] ?? null;
            $mediaKey = $mediaMessage['mediaKey'] ?? null;
            $mimetype = $mediaMessage['mimetype'] ?? null;
            $fileEncSHA256 = $mediaMessage['fileEncSha256'] ?? null;
            $fileSHA256 = $mediaMessage['fileSha256'] ?? null;
            $fileLength = $mediaMessage['fileLength'] ?? 0;

            if (! $url || ! $mediaKey) {
                return ['error' => 'Missing media URL or key'];
            }

            // Check if media download is enabled
            if (! config('wuz.download_media')) {
                return [
                    'downloaded' => false,
                    'error' => 'Media download disabled in configuration',
                ];
            }

            // Convert mediaKey and hashes to base64 if they're arrays
            $mediaKeyBase64 = is_array($mediaKey) ? base64_encode(implode('', array_map('chr', $mediaKey))) : $mediaKey;
            $fileEncSHA256Base64 = is_array($fileEncSHA256) ? base64_encode(implode('', array_map('chr', $fileEncSHA256))) : $fileEncSHA256;
            $fileSHA256Base64 = is_array($fileSHA256) ? base64_encode(implode('', array_map('chr', $fileSHA256))) : $fileSHA256;

            // Create WuzService instance with device token
            $wuzService = new WuzService(userToken: $device->token);

            // Download based on type
            $result = match ($type) {
                'image' => $wuzService->downloadImage($url, $directPath, $mediaKeyBase64, $mimetype, $fileEncSHA256Base64, $fileSHA256Base64, $fileLength),
                'video' => $wuzService->downloadVideo($url, $directPath, $mediaKeyBase64, $mimetype, $fileEncSHA256Base64, $fileSHA256Base64, $fileLength),
                'document' => $wuzService->downloadDocument($url, $directPath, $mediaKeyBase64, $mimetype, $fileEncSHA256Base64, $fileSHA256Base64, $fileLength),
                default => null,
            };

            if (isset($result['code']) && $result['code'] === 200 && isset($result['data']['Data'])) {
                $base64Data = $result['data']['Data'];

                // Remove data URI scheme if present
                if (str_contains($base64Data, 'base64,')) {
                    $base64Data = explode('base64,', $base64Data)[1];
                }

                $fileData = base64_decode($base64Data);
                $extension = explode('/', $mimetype)[1] ?? 'bin';
                // Clean up extension
                $extension = explode(';', $extension)[0];

                $fileName = 'media/'.$device->id.'/'.uniqid().'.'.$extension;

                Storage::disk('public')->put($fileName, $fileData);

                return [
                    'downloaded' => true,
                    'type' => $type,
                    'mimetype' => $mimetype,
                    'file_length' => $fileLength,
                    'path' => $fileName,
                    'url' => Storage::url($fileName),
                ];
            }

            return [
                'downloaded' => false,
                'error' => 'Failed to download media: Invalid response format',
                'response' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to download media: '.$e->getMessage());

            return [
                'downloaded' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle disconnected event
     */
    private function handleDisconnected(Device $device): void
    {
        $device->update(['connected' => false]);
    }

    /**
     * Handle logged out event
     */
    private function handleLoggedOut(Device $device): void
    {
        $device->update([
            'connected' => false,
            'jid' => null,
        ]);
    }
}
