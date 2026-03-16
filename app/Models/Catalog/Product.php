<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'price',
        'description',
        'checkout_url',
        'stock',
        'rating',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->usingSuffixGenerator(
                fn (string $slug, int $iteration) => bin2hex(random_bytes(4))
            );
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_relations', 'product_id', 'product_category_id');
    }
}
