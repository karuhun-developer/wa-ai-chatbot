<?php

namespace App\Actions\Cms\Device\Device;

use App\Models\Wuz\Device;
use App\Services\WuzService;

class StatusDeviceAction
{
    public function __construct(
        public WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(Device $device)
    {
        // Set up wuz token
        $this->wuzService = new WuzService(
            userToken: $device->token,
        );

        // Check session status
        $data = [];
        try {
            $data = $this->wuzService->sessionStatus();
            $this->wuzService->setWebhookEvents($device->token);
        } catch (\Exception $e) {
            // Do nothing
        }

        // Update device status
        $device->update([
            'connected' => $data['data']['loggedIn'] ?? false,
            'jid' => $data['data']['jid'] ?? null,
        ]);

        return $data;
    }
}
