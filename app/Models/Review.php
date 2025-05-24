<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{//demo
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'rating', 'comment', 'image'];

    public static function countByProductId($productId)
    {
        return self::where('product_id', $productId)->count();
    }

    // Phương thức lấy các đánh giá của sản phẩm với phân trang
    public static function getReviewsByProductId($productId, $perPage = 3)
    {
        return self::where('product_id', $productId)
                    ->orderBy('updated_at', 'desc')
                    ->paginate($perPage);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}