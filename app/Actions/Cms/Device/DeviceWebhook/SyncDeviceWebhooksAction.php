<?php

namespace App\Actions\Cms\Device\DeviceWebhook;

use App\Models\Wuz\Device;
use Illuminate\Support\Facades\DB;

class SyncDeviceWebhooksAction
{
    /**
     * Handle the action.
     */
    public function handle(Device $device, array $webhooks): void
    {
        DB::transaction(function () use ($device, $webhooks) {
            foreach ($webhooks as $webhookData) {
                $event = $webhookData['event'];
                $url = $webhookData['url'] ?? null;
                $status = $webhookData['status'] ?? false;

                // Create or update webhook
                $device->webhooks()->updateOrCreate(
                    ['event' => $event],
                    [
                        'url' => $url,
                        'status' => $status,
                    ]
                );
            }
        });
    }
}
