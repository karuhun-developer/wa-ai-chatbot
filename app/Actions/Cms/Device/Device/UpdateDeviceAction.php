<?php

namespace App\Actions\Cms\Device\Device;

use App\Models\Wuz\Device;

class UpdateDeviceAction
{
    /**
     * Handle the action.
     */
    public function handle(Device $device, array $data): bool
    {
        return $device->update($data);
    }
}
