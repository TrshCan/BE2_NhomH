<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Deal_ProductsSeeder extends Seeder
{
    public function run()
    {
        DB::table('deal_products')->insert([
            [
                'product_id' => 1, // đảm bảo sản phẩm này tồn tại trong bảng products
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(6),
                'created_at' => now(),
                'updated_at' => now(),

            ],
            // bạn có thể thêm nhiều bản ghi khác ở đây
        ]);
    }
}
