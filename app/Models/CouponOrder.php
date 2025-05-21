<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
class CouponOrder extends Model
{
    //
    public function up() {
        Schema::create('coupon_orders', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('coupon_code');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }
}
