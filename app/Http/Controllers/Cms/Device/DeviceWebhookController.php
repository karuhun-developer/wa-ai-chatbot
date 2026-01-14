<?php

namespace App\Http\Controllers\Cms\Device;

use App\Actions\Cms\Device\DeviceWebhook\SyncDeviceWebhooksAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Device\DeviceWebhook\SyncDeviceWebhooksRequest;
use App\Models\Wuz\Device;
use Illuminate\Support\Facades\Gate;

class DeviceWebhookController extends Controller
{
    protected string $resource = Device::class;

    /**
     * Display the webhook management modal.
     */
    public function manage(Device $device)
    {
        Gate::authorize('update'.$this->resource);

        $webhooks = $device->webhooks()->get()->keyBy('event');

        return inertia('cms/device/webhook/Manage', [
            'device' => $device,
            'webhooks' => $webhooks,
        ]);
    }

    /**
     * Sync webhooks for the device.
     */
    public function sync(SyncDeviceWebhooksRequest $request, Device $device, SyncDeviceWebhooksAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($device, $request->validated()['webhooks']);

        return back();
    }
}
