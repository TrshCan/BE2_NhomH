<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealProduct extends Model
{
    protected $table = 'deal_products';
    protected $fillable = ['product_id', 'start_date', 'end_date'];
    protected $dates = ['start_date', 'end_date'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
