<?php

namespace App\Jobs\Wuz;

use App\Ai\Agents\WuzAgent;
use App\Models\Wuz\Device;
use App\Models\Wuz\DeviceContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HandleAiReplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Device $device,
        public readonly DeviceContact $contact,
        public readonly string $chatLid,
        public readonly string $phone,
        public readonly string $messageContent,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! $this->device || ! $this->device->ai_enabled || ! $this->device->connected) {
            return;
        }

        try {
            // If a conversation doesn't exist yet, continueLastConversation or start fresh
            $response = (new WuzAgent)
                ->forUser((object) ['id' => $this->contact->id])
                ->continueLastConversation((object) ['id' => $this->contact->id])
                ->prompt(prompt: $this->messageContent);

            if ($response->text) {
                // Store message in database
                DeviceMessage::create([
                    'device_id' => $this->device->id,
                    'device_contact_id' => $this->contact->id,
                    'chat_jid' => $this->chatLid,
                    'sender_jid' => $this->device->jid,
                    'message' => $this->messageContent,
                    'metadata' => [],
                    'type' => 'text',
                ]);

                // Dispatch to a queue to avoid blocking the request and to simulate typing presence
                dispatch(
                    new SendWuzMessageJob(
                        deviceToken: $this->device->token,
                        phone: $this->phone,
                        type: 'text',
                        messageContent: $response->text,
                        mediaData: null,
                        linkPreview: false,
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error('AI auto-reply failed: '.$e->getMessage());
        }
    }
}
