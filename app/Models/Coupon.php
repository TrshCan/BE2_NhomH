<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'is_active'];
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'coupon_order');
    }
}
