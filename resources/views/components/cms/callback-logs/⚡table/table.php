<?php

use App\Livewire\BaseComponent;
use App\Models\Menu\Menu;
use App\Models\Wuz\CallbackLog;
use Illuminate\Support\Facades\Gate;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Menu::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Event',
            'field' => 'event_type',
        ],
        [
            'name' => 'Ip Address',
            'field' => 'ip_address',
        ],
        [
            'name' => 'Created At',
            'field' => 'created_at',
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
        $model = CallbackLog::with('device')
            ->when(! auth()->user()->isSuperAdmin(), function ($query) {
                $query->whereHas('device', function ($query) {
                    $query->where('user_id', auth()->id());
                });
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
};
