<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    // Đảm bảo chỉ các trường này có thể gán giá trị
    protected $fillable = ['image_url', 'product_id'];

    // Ẩn các trường nhạy cảm (tùy chỉnh theo nhu cầu)
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Mối quan hệ một ảnh thuộc về một sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // 'product_id' là khóa ngoại trong bảng images
    }
}
