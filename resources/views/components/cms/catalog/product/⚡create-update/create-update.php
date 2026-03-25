<?php

use App\Actions\Cms\Catalog\Product\StoreProductAction;
use App\Actions\Cms\Catalog\Product\UpdateProductAction;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    // Model instance
    public $modelInstance = Product::class;

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
    public function categories()
    {
        return ProductCategory::all();
    }

    // Record data
    public $id;

    public $oldData;

    public $sku;

    public $name;

    public $price;

    public $description;

    public $checkout_url;

    public $stock;

    public $rating;

    public $product_categories = [];

    public $image;

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = Product::findOrFail($id);
        $this->oldData = $record;
        $this->fill(
            $record->only(
                'id',
                'sku',
                'name',
                'price',
                'description',
                'checkout_url',
                'stock',
                'rating',
            )
        );
        $this->product_categories = $record->productCategories->pluck('id')->toArray();
        $this->price = numberToCurrency($this->price);
        $this->stock = numberToCurrency($this->stock);
        $this->reset('image');
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'sku',
            'name',
            'price',
            'description',
            'checkout_url',
            'stock',
            'rating',
            'product_categories',
            'image',
        ]);

        $this->price = 0;
        $this->rating = 0;
    }

    // Handle form submit
    public function submit(StoreProductAction $storeAction, UpdateProductAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->price = currencyToNumber($this->price);
        $this->stock = currencyToNumber($this->stock);

        $this->validate([
            'sku' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'numeric|min:0',
            'description' => 'nullable|string',
            'checkout_url' => 'nullable|url',
            'stock' => 'nullable|integer|min:0',
            'product_categories' => 'array',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $submitData = $this->all();
        // Remove rating from submit data since it's readonly
        unset($submitData['rating']);

        if ($this->isUpdate) {
            $updateAction->handle(
                product: Product::findOrFail($this->id),
                data: $submitData,
            );
        } else {
            $storeAction->handle(
                data: $submitData,
            );
        }

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Product updated successfully.' : 'Product created successfully.'
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();

        // Reset record data
        $this->resetRecordData();
    }
};
