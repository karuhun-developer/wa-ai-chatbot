<?php

namespace App\Jobs\Wuz;

use App\Services\WuzService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWuzMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $deviceToken,
        public readonly string $phone,
        public readonly string $type,
        public readonly string $messageContent,
        public readonly ?string $mediaData = null,
        public readonly bool $linkPreview = false,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $wuzService = new WuzService(userToken: $this->deviceToken);

        if ($this->type === 'text') {
            $wuzService->sendChatPresence($this->phone, 'composing');

            sleep($this->getTypingSeconds($this->messageContent));

            $wuzService->sendChatPresence($this->phone, 'paused');

            $wuzService->sendMessageText($this->phone, $this->messageContent, $this->linkPreview);
        } elseif ($this->type === 'image') {
            if (! empty($this->messageContent)) {
                $wuzService->sendChatPresence($this->phone);

                sleep($this->getTypingSeconds($this->messageContent));

                $wuzService->sendChatPresence($this->phone, 'paused');
            }

            $wuzService->sendMessageImage($this->phone, $this->mediaData, $this->messageContent);
        } elseif ($this->type === 'video') {
            if (! empty($this->messageContent)) {
                $wuzService->sendChatPresence($this->phone);

                sleep($this->getTypingSeconds($this->messageContent));

                $wuzService->sendChatPresence($this->phone, 'paused');
            }

            $wuzService->sendMessageVideo($this->phone, $this->mediaData, $this->messageContent);
        }
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

        return (int) clamp($duration, 1, 25);
    }
}
