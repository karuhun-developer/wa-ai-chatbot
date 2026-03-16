<?php

namespace App\Actions\Cms\Catalog\ProductCategory;

use App\Models\Catalog\ProductCategory;
use App\Traits\WithMediaCollection;
use Illuminate\Http\UploadedFile;

class StoreProductCategoryAction
{
    use WithMediaCollection;

    /**
     * Handle the action.
     */
    public function handle(array $data): ProductCategory
    {
        $productCategory = ProductCategory::create($data);

        // Save media
        if ($data['image'] ?? null instanceof UploadedFile) {
            $this->saveMedia(
                model: $productCategory,
                file: $data['image'],
                collection: 'image',
            );
        }

        return $productCategory;
    }
}
