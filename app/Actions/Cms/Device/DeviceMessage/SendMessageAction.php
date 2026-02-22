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
                    // $jidData = $wuzService->phoneToJid($phone);

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
                        $messageContent = $data['message'];
                        $linkPreview = isset($data['link_preview']) ? (bool) $data['link_preview'] : false;

                        // Dispatch to a queue to avoid blocking the request and to simulate typing presence
                        dispatch(function () use ($wuzService, $phone, $messageContent, $linkPreview) {
                            $wuzService->sendChatPresence($phone, 'composing');

                            // Simulate typing delay based on message content
                            sleep($this->getTypingSeconds($messageContent));

                            $wuzService->sendChatPresence($phone, 'paused');

                            // Send text message
                            $wuzService->sendMessageText(
                                $phone,
                                $messageContent,
                                $linkPreview,
                            );
                        });

                        break;

                    case 'image':
                        // Convert uploaded image to base64
                        $imageFile = $data['image'];
                        $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
                        $mimeType = $imageFile->getMimeType();
                        $base64Image = "data:{$mimeType};base64,{$imageData}";
                        $messageContent = $data['caption'] ?? '';

                        // Dispatch to a queue to avoid blocking the request and to simulate typing presence if caption exists
                        dispatch(function () use ($wuzService, $phone, $base64Image, $messageContent) {
                            if (! empty($messageContent)) {
                                $wuzService->sendChatPresence($phone);

                                // Simulate typing delay based on caption content
                                sleep($this->getTypingSeconds($messageContent));

                                $wuzService->sendChatPresence($phone, 'paused');
                            }

                            // Send image message
                            $wuzService->sendMessageImage(
                                $phone,
                                $base64Image,
                                $messageContent,
                            );
                        });

                        break;

                    case 'video':
                        // Convert uploaded video to base64
                        $videoFile = $data['video'];
                        $videoData = base64_encode(file_get_contents($videoFile->getRealPath()));
                        $mimeType = $videoFile->getMimeType();
                        $base64Video = "data:{$mimeType};base64,{$videoData}";
                        $messageContent = $data['caption'] ?? '';

                        // Dispatch to a queue to avoid blocking the request and to simulate typing presence if caption exists
                        dispatch(function () use ($wuzService, $phone, $base64Video, $messageContent) {
                            if (! empty($messageContent)) {
                                $wuzService->sendChatPresence($phone);

                                // Simulate typing delay based on caption content
                                sleep($this->getTypingSeconds($messageContent));

                                $wuzService->sendChatPresence($phone, 'paused');
                            }

                            $wuzService->sendMessageVideo(
                                $phone,
                                $base64Video,
                                $messageContent,
                            );
                        });
                        break;

                        // case 'button':
                        //     $response = $wuzService->sendMessageButton(
                        //         $phone,
                        //         $data['message'],
                        //         $data['buttons'] ?? []
                        //     );
                        //     $messageContent = $data['message'];
                        //     break;
                    default:
                        throw new \InvalidArgumentException('Unsupported message type: '.$messageType);
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

    /**
     * Estimate typing duration based on message content.
     */
    private function getTypingSeconds(string $text): int
    {
        $words = str_word_count($text);
        $symbols = preg_match_all('/[^\w\s]/', $text);
        $breaks = substr_count($text, "\n");

        $duration = ($words * 0.3) + ($symbols * 0.2) + ($breaks * 0.5);

        return (float) clamp($duration, 0.5, 25);
    }
}
