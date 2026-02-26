<?php

namespace App\Actions\Cms\Device\Device;

use App\Models\Wuz\Device;
use App\Services\WuzService;

class DisconnectDeviceAction
{
    public function __construct(
        public WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(Device $device): void
    {
        // Set up wuz token
        $this->wuzService = new WuzService(
            userToken: $device->token,
        );

        // Connect device
        try {
            $this->wuzService->sessionLogout();
        } catch (\Exception $e) {
            // Do nothing
        }
    }
}
