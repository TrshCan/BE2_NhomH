<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            DB::table('orders')->insert([
                'user_id' => $faker->numberBetween(1, 10),
                'order_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'total_amount' => $faker->randomFloat(2, 100, 5000),
                'status' => $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
                'shipping_address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
