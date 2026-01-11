<?php

namespace App\Actions\Cms\Device\Device;

use App\Models\Wuz\Device;
use App\Services\WuzService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreDeviceAction
{
    public function __construct(
        public readonly WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(array $data): Device
    {
        return DB::transaction(function () use ($data) {
            // Generate token for WUZ API
            $token = 'device-'.uniqid().time();

            // Create device in WUZ API
            try {
                $device = $this->wuzService->addUser(
                    name: $data['name'],
                    token: $token,
                );

                $data['user_id'] = auth()->id();
                $data['device_id'] = $device['data']['id'];
                $data['token'] = $token;
            } catch (\Exception $e) {
                throw new ValidationException('Failed to create device: '.$e->getMessage());
            }

            return Device::create($data);
        });
    }
}
