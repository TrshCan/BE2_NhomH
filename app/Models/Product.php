<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'is_featured', // Đã thêm
        'stock_quantity',
        'category_id',
        'brand_id',
        'image_url',
        'sales_count',
    ];

    public function scopeFeatured($query, $limit = 3)
    {
        return $query->where('is_featured', true)->take($limit);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeSearch($query, $search)
    {
        return $search ? $query->where('product_name', 'like', '%' . $search . '%') : $query;
    }

    public function scopeBestSellers($query, $limit = 6)
    {
        return $query->orderByDesc('sales_count')->take($limit);
    }

    public function scopeFindWithDetails($query, $id)
    {
        return $query->with('details', 'images')->findOrFail($id);
    }

    public function scopeDealOfTheWeek($query)
    {
        return $query->whereHas('deal', function ($q) {
            $q->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        });
    }
    public function scopeByBrand($query, $brandId)
    {
        return $brandId ? $query->where('id', $brandId) : $query;
    }
    public function scopeFilter(Builder $query, $brandSlug = null, $categorySlug = null)
    {
        if ($brandSlug) {
            $brand = \App\Models\Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($categorySlug) {
            $query->where('category_id', $categorySlug);
        }

        return $query;
    }


    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function details()
    {
        return $this->hasOne(ProductDetail::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function deal()
    {
        return $this->hasOne(DealProduct::class, 'product_id');
    }
}
