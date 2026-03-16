<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\Product;

class UpdateProductAction
{
    /**
     * Handle the action.
     */
    public function handle(Product $product, array $data): bool
    {
        $updated = $product->update($data);

        if (isset($data['product_categories'])) {
            $product->productCategories()->sync($data['product_categories']);
        }

        return $updated;
    }
}
