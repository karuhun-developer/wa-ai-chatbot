<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\Product;

class DeleteProductAction
{
    /**
     * Handle the action.
     */
    public function handle(Product $product): ?bool
    {
        return $product->delete();
    }
}
