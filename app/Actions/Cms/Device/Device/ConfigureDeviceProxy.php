<?php

namespace App\Actions\Cms\Device\Device;

use App\Models\Wuz\Device;
use App\Services\WuzService;
use Illuminate\Support\Facades\DB;

class ConfigureDeviceProxy
{
    public function __construct(
        public WuzService $wuzService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(Device $device, array $proxy): void
    {
        // Set up wuz token
        $this->wuzService = new WuzService(
            userToken: $device->token,
        );

        // Configure Proxy settings in Wuz
        DB::transaction(function () use ($device, $proxy) {
            if (! empty($proxy['proxy_url'] ?? null)) {
                $this->wuzService->setProxy(
                    proxyUrl: $proxy['proxy_url'] ?? null,
                    enable: $proxy['proxy_enabled'] ?? false,
                );
            }

            // Update device proxy settings
            $device->update([
                'proxy_url' => $proxy['proxy_url'] ?? null,
                'proxy_enabled' => $proxy['proxy_enabled'] ?? false,
            ]);
        });
    }
}
