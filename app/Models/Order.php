<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'order_date',
        'total_amount',
        'status',
        'shipping_address'
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_order');
    }
   

    public static function getOrdersWithinDateRange($fromDate, $toDate)
    {
        return self::whereBetween('order_date', [$fromDate, $toDate])
            ->where('status', '!=', 'canceled')
            ->get();
    }
  
    public static function getMonthlyRevenue($fromDate, $toDate, $categoryId = null)
    {
        $query = self::whereBetween('order_date', [$fromDate, $toDate])
            ->where('status', '!=', 'canceled')
            ->select(
                DB::raw('DATE_FORMAT(order_date, "%m/%Y") as month'),
                DB::raw('SUM(total_amount) as value')
            )
            ->groupBy('month')
            ->orderBy('month');
        
        if ($categoryId) {
            $query->whereHas('orderDetails.product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        return $query->get();
    }

    public static function getOrderStatusDistribution($fromDate, $toDate, $categoryId = null)
    {
        $query = self::whereBetween('order_date', [$fromDate, $toDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status');

        if ($categoryId) {
            $query->whereHas('orderDetails.product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        return $query->get();
    }

}
