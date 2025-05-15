<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'brand_id',
        'image_url',
        'sales_count',
    ];
    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');  // 'product_id' là khóa ngoại trong bảng images
    }
    public function details()
    {
        return $this->hasOne(ProductDetail::class, 'product_id');
    }
    // In Product.php model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(brand::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product', 'product_id', 'cart_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

}
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
