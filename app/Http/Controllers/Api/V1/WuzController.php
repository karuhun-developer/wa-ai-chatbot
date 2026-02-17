<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Cms\Device\Device\StatusDeviceAction;
use App\Actions\Cms\Device\DeviceMessage\SendMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Wuz\SendMessageRequest;
use App\Models\Wuz\Device;

class WuzController extends Controller
{
    /**
     * Send a message.
     */
    public function send(SendMessageRequest $request, SendMessageAction $action, StatusDeviceAction $statusAction)
    {
        $device = Device::where('device_id', $request->token)->firstOrFail();

        // Handle device status before sending message
        $statusAction->handle($device);

        return $this->responseWithCreated($action->handle($device, $request->validated()));
    }
}
