<?php

namespace App\Ai\Tools;

use App\Models\Catalog\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetProduct implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Mengambil daftar produk dari toko. Bisa difilter berdasarkan nama produk, kategori, harga minimum, dan harga maksimum. Gunakan tool ini ketika user bertanya tentang produk apa saja yang tersedia, mencari produk tertentu, atau ingin tahu harga produk.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = Product::query()->with('productCategories');

        if ($name = $request->get('name')) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if ($category = $request->get('category')) {
            $query->whereHas('productCategories', function ($q) use ($category) {
                $q->where('name', 'like', '%'.$category.'%');
            });
        }

        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return 'Tidak ada produk yang ditemukan dengan filter tersebut.';
        }

        $result = $products->map(function (Product $product) {
            $categories = $product->productCategories->pluck('name')->join(', ');

            return sprintf(
                '- %s | Harga: Rp %s | Stok: %d | Kategori: %s | Rating: %.1f',
                $product->name,
                number_format($product->price, 0, ',', '.'),
                $product->stock,
                $categories ?: '-',
                $product->rating ?? 0,
            );
        })->join("\n");

        return "Ditemukan {$products->count()} produk:\n".$result;
    }

    /**
     * Get the tool's schema definition.
     *
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Filter berdasarkan nama produk (pencarian parsial).')->nullable(),
            'category' => $schema->string()->description('Filter berdasarkan nama kategori produk (pencarian parsial).')->nullable(),
            'min_price' => $schema->number()->description('Harga minimum produk dalam Rupiah.')->nullable(),
            'max_price' => $schema->number()->description('Harga maksimum produk dalam Rupiah.')->nullable(),
        ];
    }
}
