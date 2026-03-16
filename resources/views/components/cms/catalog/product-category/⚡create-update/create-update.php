<?php

use App\Actions\Cms\Catalog\ProductCategory\StoreProductCategoryAction;
use App\Actions\Cms\Catalog\ProductCategory\UpdateProductCategoryAction;
use App\Models\Catalog\ProductCategory;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // Model instance
    public $modelInstance = ProductCategory::class;

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

    #[Computed]
    public function locales()
    {
        return getLocales();
    }

    // Record data
    public $id;

    public $name;

    public $status;

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = ProductCategory::findOrFail($id);
        $this->fill(
            $record->only(
                'id',
                'name',
            )
        );
        $this->status = $record->status->value === 1;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'name',
            'status',
        ]);

        $this->status = true;
    }

    // Handle form submit
    public function submit(StoreProductCategoryAction $storeAction, UpdateProductCategoryAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        if ($this->isUpdate) {
            $updateAction->handle(
                productCategory: ProductCategory::findOrFail($this->id),
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
            message: $this->isUpdate ? 'Product Category updated successfully.' : 'Product Category created successfully.'
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
