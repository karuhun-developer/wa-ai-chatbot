<?php

use App\Actions\Cms\Device\Device\DeleteDeviceAction;
use App\Actions\Cms\Device\Device\DisconnectDeviceAction;
use App\Livewire\BaseComponent;
use App\Models\Wuz\Device;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Device::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Role',
            'field' => 'roles.name',
        ],
        [
            'name' => 'Menu',
            'field' => 'menus.name',
        ],
        [
            'name' => 'URL',
            'field' => 'menus.url',
        ],
        [
            'name' => 'Icon',
            'field' => 'menus.icon',
        ],
        [
            'name' => 'Order',
            'field' => 'menus.order',
        ],
        [
            'name' => 'Active Pattern',
            'field' => 'menus.active_pattern',
        ],
        [
            'name' => 'Status',
            'field' => 'menus.status',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Device::when(! auth()->user()->isSuperAdmin(), function ($query) {
            $query->where('user_id', auth()->user()->id);
        });

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: $this->searchBy,
            orderBy: $this->paginationOrderBy,
            order: $this->paginationOrder,
            paginate: $this->paginate,
            s: $this->search,
        );

        return $this->view([
            'data' => $data,
        ]);
    }

    #[On('delete')]
    public function delete($id, DeleteDeviceAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            device: Device::findOrFail($id)
        );

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: 'Device deleted successfully.'
        );
    }

    #[On('disconnect')]
    public function disconnect(Device $device, DisconnectDeviceAction $disconnectAction)
    {
        Gate::authorize('update'.$this->modelInstance);

        $disconnectAction->handle(
            device: $device
        );

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: 'Device disconnected successfully.'
        );
    }

    #[Renderless]
    public function toggleAiEnabled(Device $device)
    {
        Gate::authorize('update'.$this->modelInstance);

        $device->update([
            'ai_enabled' => ! $device->ai_enabled,
        ]);
    }

    #[Renderless]
    public function toggleAutoRead(Device $device)
    {
        Gate::authorize('update'.$this->modelInstance);

        $device->update([
            'auto_read' => ! $device->auto_read,
        ]);
    }
};
