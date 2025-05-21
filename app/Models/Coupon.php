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

    public static function isActive(string $code): ?Coupon
    {
        return self::where('code', $code)->where('is_active', true)->first();
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where('code', 'like', '%' . $filters['search'] . '%');
        }
    }
}
