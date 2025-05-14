<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id'; // Hoặc 'product_detail_id' nếu bạn dùng khóa chính khác

    protected $fillable = ['product_id', 'model', 'connectivity', 'compatibility', 'weight'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
