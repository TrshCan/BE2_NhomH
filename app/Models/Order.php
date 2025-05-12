<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    protected $fillable = ['user_id', 'total_amount', 'status', 'shipping_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
