<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\Product;
use App\Traits\WithMediaCollection;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateProductAction
{
    use WithMediaCollection;

    /**
     * Handle the action.
     */
    public function handle(Product $product, array $data): bool
    {
        $updated = $product->update($data);

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

        return $updated;
    }
}
