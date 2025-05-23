<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $primaryKey = 'order_detail_id';
    protected $table = 'order_details';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    public static function getRevenueData($fromDate, $toDate, $categoryId = null)
    {
        $query = self::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->whereBetween('orders.order_date', [$fromDate, $toDate])
            ->where('orders.status', '!=', 'canceled')
            ->select(
                DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.order_id) as order_count')
            );

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        return $query->first();
    }

    public static function getTopProducts($fromDate, $toDate, $categoryId = null, $limit = 5)
    {
        $query = self::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->whereBetween('orders.order_date', [$fromDate, $toDate])
            ->where('orders.status', '!=', 'canceled')
            ->select(
                'products.product_id',
                'products.product_name',
                DB::raw('SUM(order_details.quantity) as quantity_sold'),
                DB::raw('SUM(order_details.quantity * order_details.price) as revenue')
            )
            ->groupBy('products.product_id', 'products.product_name')
            ->orderBy('quantity_sold', 'desc')
            ->limit($limit);

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        return $query->get();
    }

    public static function getCategoryRevenue($fromDate, $toDate, $categoryId = null)
    {
        $query = self::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->whereBetween('orders.order_date', [$fromDate, $toDate])
            ->where('orders.status', '!=', 'canceled')
            ->select(
                'categories.category_name as category',
                DB::raw('SUM(order_details.quantity * order_details.price) as value')
            )
            ->groupBy('categories.category_id', 'categories.category_name')
            ->orderBy('value', 'desc');

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        return $query->get();
    }
}

