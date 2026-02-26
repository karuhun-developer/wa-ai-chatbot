<?php

use App\Actions\Cms\Device\Device\StatusDeviceAction;
use App\Actions\Cms\Device\DeviceMessage\SendMessageAction;
use App\Models\Wuz\Device;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    // Model instance
    public $modelInstance = Device::class;

    public Device $device;

    // Record data
    public $type = 'text';

    public $phone = '';

    public $message = '';

    public $link_preview = false;

    public $image;

    public $video;

    public $caption;

    public function mount(StatusDeviceAction $statusAction)
    {
        Gate::authorize('update'.$this->modelInstance);

        // Check if the user is super admin or the owner of the device
        if (! auth()->user()->isSuperAdmin()) {
            abort_if($this->device->user_id !== auth()->id(), 403);
        }

        // Check the device status on mount to ensure it's up-to-date
        $statusAction->handle($this->device);
    }

    public function submit(StatusDeviceAction $statusAction, SendMessageAction $sendMessageAction)
    {
        Gate::authorize('update'.$this->modelInstance);

        $this->validate([
            'phone' => 'required|numeric',
            'type' => 'required|in:text,image,video,button',
            'message' => $this->type === 'text' ? 'required|string' : 'nullable',
            'image' => $this->type === 'image' ? 'required|file|mimes:jpg,jpeg,png|max:2048' : 'nullable', // 2MB max
            'video' => $this->type === 'video' ? 'required|file|mimes:mp4|max:5120' : 'nullable', // 5MB max
            'caption' => 'nullable|string',
        ]);

        // Check if device status before sending message
        $statusAction->handle($this->device);
        $sendMessageAction->handle(
            device: $this->device,
            data: $this->all(),
        );

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: 'Test message sent successfully!'
        );
    }
};
