<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{

    protected $fillable = ['name', 'description'];

    public function similarProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'similar_products', 'product_id', 'similar_product_id');
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class)->orderByDesc('offer_end');
    }

    public function activeOffers(): BelongsToMany
    {
        return $this->offers()->where('offer_start', '<=', now())->where('offer_end', '>', now());
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }

    public function stock()
    {
        return $this->stocks()->sum('qty');
    }
}
