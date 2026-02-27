<?php

namespace App\Jobs\Wuz;

use App\Ai\Agents\WuzAgent;
use App\Models\Wuz\Device;
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
            // Reconstruct JID from phone. This will be stored as `chat_jid` in history tables via WuzConversationStore
            $chatJid = "{$this->phone}@s.whatsapp.net";

            // If a conversation doesn't exist yet, continueLastConversation or start fresh
            $response = (new WuzAgent)
                ->forUser((object) ['id' => $chatJid])
                ->continueLastConversation((object) ['id' => $chatJid])
                ->prompt(prompt: $this->messageContent);

            if ($response->text) {
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
