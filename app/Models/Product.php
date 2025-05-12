<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    protected $primaryKey = 'product_id'; // nếu không phải 'id' thì cần khai báo

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

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product', 'product_id', 'cart_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

}
