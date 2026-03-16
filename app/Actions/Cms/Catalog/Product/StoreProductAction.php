<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\Product;

class StoreProductAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Product
    {
        $product = Product::create($data);

        if (isset($data['product_categories'])) {
            $product->productCategories()->sync($data['product_categories']);
        }

        return $product;
    }
}
