<?php

namespace App\Actions\Cms\Device\Device;

use App\Models\Wuz\Device;
use App\Services\WuzService;

class DeleteDeviceAction
{
    public function __construct(
        public readonly WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(Device $device): ?bool
    {
        // Delete device from WUZ API
        try {
            $this->wuzService->deleteUser($device->device_id);
        } catch (\Exception $e) {
            // Ignore
        }

        return $device->delete();
    }
}
