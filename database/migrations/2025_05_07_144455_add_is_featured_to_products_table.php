<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIsFeaturedToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Cập nhật dữ liệu hiện có
            if (Schema::hasColumn('products', 'is_featured')) {
                DB::table('products')
                    ->whereNull('is_featured')
                    ->orWhere('is_featured', '!=', 0)
                    ->orWhere('is_featured', '!=', 1)
                    ->update(['is_featured' => 0]);
                $table->boolean('is_featured')->default(false)->change();
            } else {
                $table->boolean('is_featured')->default(false)->after('price');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
}
