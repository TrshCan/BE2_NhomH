<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealProduct extends Model
{
    protected $table = 'deal_products';
    protected $fillable = ['product_id', 'start_date', 'end_date'];
    // Sử dụng casts để định dạng ngày
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public $timestamps = true;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
