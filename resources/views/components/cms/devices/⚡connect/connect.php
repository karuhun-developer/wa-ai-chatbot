<?php

use App\Actions\Cms\Device\Device\ConnectDeviceAction;
use App\Actions\Cms\Device\Device\StatusDeviceAction;
use App\Models\Wuz\Device;
use Livewire\Component;

new class extends Component
{
    // Model instance
    public $modelInstance = Device::class;

    public Device $device;

    public function mount(ConnectDeviceAction $connectAction)
    {
        Gate::authorize('update'.$this->modelInstance);

        // Check if the user is super admin or the owner of the device
        if (! auth()->user()->isSuperAdmin()) {
            abort_if($this->device->user_id !== auth()->id(), 403);
        }

        // Initiate the device connection process
        $connectAction->handle($this->device);
    }

    public function render(StatusDeviceAction $statusAction)
    {
        // Fetch status (including QR)
        $status = $statusAction->handle($this->device);

        // Ensure status has default structure if empty
        if (empty($status)) {
            $status = [
                'success' => false,
                'code' => 500,
                'data' => [
                    'connected' => false,
                    'loggedIn' => false,
                    'qrcode' => null,
                ],
            ];
        }

        return $this->view([
            'status' => $status,
        ]);
    }
};
