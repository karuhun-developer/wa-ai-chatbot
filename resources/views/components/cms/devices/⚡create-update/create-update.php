<?php

use App\Actions\Cms\Device\Device\StoreDeviceAction;
use App\Actions\Cms\Device\Device\UpdateDeviceAction;
use App\Actions\Cms\Device\DeviceWebhook\SyncDeviceWebhooksAction;
use App\Models\Wuz\Device;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // Model instance
    public $modelInstance = Device::class;

    public $isUpdate = false;

    #[On('set-action')]
    public function setAction($id = null)
    {
        if ($id) {
            $this->isUpdate = true;
            $this->getRecordData($id);
        } else {
            $this->isUpdate = false;
            $this->resetRecordData();
        }
    }

    // Record data
    public $id;

    public $name;

    public $ai_enabled;

    public $auto_read;

    public $webhook;

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = Device::with('webhooks')->find($id);
        $this->id = $record->id;
        $this->name = $record->name;
        $this->ai_enabled = $record->ai_enabled;
        $this->auto_read = $record->auto_read;

        // Load webhooks
        $this->webhook = [
            'All' => [
                'url' => '',
                'status' => false,
            ],
            'MessageSent' => [
                'url' => '',
                'status' => false,
            ],
            'MessageReceived' => [
                'url' => '',
                'status' => false,
            ],
            'MessageRead' => [
                'url' => '',
                'status' => false,
            ],
            'MessageDeleted' => [
                'url' => '',
                'status' => false,
            ],
        ];

        foreach ($record->webhooks as $webhook) {
            $this->webhook[$webhook->event] = [
                'url' => $webhook->url,
                'status' => $webhook->status,
            ];
        }
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'name',
            'ai_enabled',
            'auto_read',
            'webhook',
        ]);

        $this->ai_enabled = false;
        $this->auto_read = false;
        $this->webhook = [
        ];
    }

    // Handle form submit
    public function submit(StoreDeviceAction $storeAction, UpdateDeviceAction $updateAction)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'ai_enabled' => 'required|boolean',
            'auto_read' => 'required|boolean',
        ]);

        if ($this->isUpdate) {
            $updateAction->handle(
                device: Device::findOrFail($this->id),
                data: $this->all(),
            );
        } else {
            $storeAction->handle(
                data: $this->all(),
            );
        }

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Device updated successfully' : 'Device created successfully',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }

    public function saveWebhooks(SyncDeviceWebhooksAction $syncWebhooksAction)
    {
        $syncWebhooksAction->handle(
            device: Device::findOrFail($this->id),
            webhooks: $this->webhook,
        );

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: 'Webhooks updated successfully',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('webhookModal')->close();
    }
};
