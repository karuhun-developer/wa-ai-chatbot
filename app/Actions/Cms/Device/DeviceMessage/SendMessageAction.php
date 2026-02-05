<?php

namespace App\Actions\Cms\Device\DeviceMessage;

use App\Models\Wuz\Device;
use App\Models\Wuz\DeviceMessage;
use App\Models\Wuz\PhoneJid;
use App\Services\WuzService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendMessageAction
{
    public function __construct(
        public readonly WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(Device $device, array $data): DeviceMessage
    {
        return DB::transaction(function () use ($device, $data) {
            // Normalize phone number
            $phone = normalizePhoneNumber($data['phone']);

            // Get or create phone JID
            $phoneJid = PhoneJid::firstOrCreate(
                ['phone' => $phone],
                ['jid' => null, 'lid' => null]
            );

            // If JID not exists, fetch from API
            if (empty($phoneJid->jid)) {
                try {
                    $wuzService = new WuzService(userToken: $device->token);
                    $jidData = $wuzService->phoneToJid($phone);

                    if (isset($jidData['data'])) {
                        $phoneJid->update([
                            'jid' => $jidData['data']['jid'] ?? null,
                            'lid' => $jidData['data']['lid'] ?? null,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log error but continue
                    \Log::error('Failed to get JID for phone: '.$phone, ['error' => $e->getMessage()]);
                }
            }

            // Send message based on type
            $wuzService = new WuzService(userToken: $device->token);
            $response = null;
            $messageType = $data['type'] ?? 'text';
            $messageContent = '';

            try {
                switch ($messageType) {
                    case 'text':
                        $wuzService->sendChatPresence($phone);
                        $response = $wuzService->sendMessageText(
                            $phone,
                            $data['message']
                        );
                        $messageContent = $data['message'];
                        break;

                    case 'image':
                        // Convert uploaded image to base64
                        $imageFile = $data['image'];
                        $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
                        $mimeType = $imageFile->getMimeType();
                        $base64Image = "data:{$mimeType};base64,{$imageData}";

                        $wuzService->sendChatPresence($phone);
                        $response = $wuzService->sendMessageImage(
                            $phone,
                            $base64Image,
                            $data['caption'] ?? ''
                        );
                        $messageContent = $data['caption'] ?? 'Image';
                        break;

                    case 'video':
                        // Convert uploaded video to base64
                        $videoFile = $data['video'];
                        $videoData = base64_encode(file_get_contents($videoFile->getRealPath()));
                        $mimeType = $videoFile->getMimeType();
                        $base64Video = "data:{$mimeType};base64,{$videoData}";

                        $wuzService->sendChatPresence($phone);
                        $response = $wuzService->sendMessageVideo(
                            $phone,
                            $base64Video,
                            $data['caption'] ?? ''
                        );
                        $messageContent = $data['caption'] ?? 'Video';
                        break;

                    case 'button':
                        $response = $wuzService->sendMessageButton(
                            $phone,
                            $data['message'],
                            $data['buttons'] ?? []
                        );
                        $messageContent = $data['message'];
                        break;
                }
            } catch (\Exception $e) {
                Log::error('Failed to send message', ['error' => $e->getMessage()]);
                throw $e;
            }

            // Store message in device_messages
            return DeviceMessage::create([
                'device_id' => $device->id,
                'chat_jid' => $phoneJid->jid,
                'sender_jid' => $device->jid,
                'message' => $messageContent,
                'metadata' => $response,
                'type' => $messageType,
            ]);
        });
    }
}
