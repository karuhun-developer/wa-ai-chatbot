<?php

namespace App\Http\Controllers\Cms\Device;

use App\Actions\Cms\Device\DeviceMessage\SendMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Device\DeviceMessage\SendMessageRequest;
use App\Models\Wuz\Device;
use Illuminate\Support\Facades\Gate;

class DeviceMessageController extends Controller
{
    protected string $resource = Device::class;

    /**
     * Display the test message page.
     */
    public function test(Device $device)
    {
        Gate::authorize('update'.$this->resource);

        return inertia('cms/device/message/Test', [
            'device' => $device,
        ]);
    }

    /**
     * Send a message.
     */
    public function send(SendMessageRequest $request, Device $device, SendMessageAction $action)
    {
        Gate::authorize('update'.$this->resource);

        $action->handle($device, $request->validated());

        return back();
    }
}
