<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponOrder extends Model
{
    protected $table = 'coupon_order';
    protected $fillable = ['coupon_id', 'order_id', 'user_id', 'total'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
