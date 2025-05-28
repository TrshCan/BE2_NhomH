<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $faker = Faker::create();

        $couponTypes = ['percent', 'fixed'];
        
        for ($i = 0; $i < 100; $i++) { // Create 20 random coupons
            DB::table('coupons')->insert([
                'code' => strtoupper($faker->lexify('??????')), // Generates a random 6-letter coupon code
                'type' => $faker->randomElement($couponTypes), // Randomly select percent or fixed type
                'value' => $faker->randomElement([5, 10, 15, 20, 25, 30, 50, 90]), // Discount value
                'is_active' => $faker->boolean(80), // 80% chance of being active
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
