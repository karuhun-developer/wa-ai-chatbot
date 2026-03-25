<?php

use App\Actions\Cms\Catalog\Product\DeleteProductAction;
use App\Livewire\BaseComponent;
use App\Models\Catalog\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Product::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'name',
        ],
        [
            'name' => 'SKU',
            'field' => 'sku',
        ],
        [
            'name' => 'Price',
            'field' => 'price',
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
        $data = $this->getDataWithFilter(
            model: Product::with('media'),
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
    public function delete($id, DeleteProductAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            product: Product::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: 'Product deleted successfully',
        );
    }
};
