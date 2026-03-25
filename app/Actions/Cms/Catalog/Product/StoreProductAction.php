<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\Product;
use App\Traits\WithMediaCollection;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StoreProductAction
{
    use WithMediaCollection;

    /**
     * Handle the action.
     */
    public function handle(array $data): Product
    {
        $product = Product::create($data);

        if (isset($data['product_categories'])) {
            $product->productCategories()->sync($data['product_categories']);
        }

        // Handle media collection
        if ($data['image'] ?? null instanceof UploadedFile || $data['image'] ?? null instanceof TemporaryUploadedFile) {
            $this->saveMedia(
                model: $product,
                file: $data['image'],
                collection: 'image',
            );
        }

        return $product;
    }
}
