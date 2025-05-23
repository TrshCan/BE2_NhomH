<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('coupons')->insert([
            // Existing entries (for context, you'll append these to your current seeder)
            [
                'code' => 'SALE10',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 30000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WELCOME50',
                'type' => 'percent',
                'value' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SAVE100K',
                'type' => 'fixed',
                'value' => 100000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SPRING20',
                'type' => 'percent',
                'value' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GET50KOFF',
                'type' => 'fixed',
                'value' => 50000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SUMMER30',
                'type' => 'percent',
                'value' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BUYNOW25',
                'type' => 'percent',
                'value' => 25,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FLASHSALE',
                'type' => 'percent',
                'value' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BIGSAVINGS',
                'type' => 'fixed',
                'value' => 75000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NEWUSER15',
                'type' => 'percent',
                'value' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WEEKENDDEAL',
                'type' => 'fixed',
                'value' => 20000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'AUTUMN40',
                'type' => 'percent',
                'value' => 40,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HAPPYHOUR',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HOLIDAYGIFT',
                'type' => 'fixed',
                'value' => 150000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SPECIALDISC',
                'type' => 'percent',
                'value' => 22,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GET200K',
                'type' => 'fixed',
                'value' => 200000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOYALTY10',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'APPEXCLUSIVE',
                'type' => 'fixed',
                'value' => 40000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CYBERMONDAY',
                'type' => 'percent',
                'value' => 35,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WINTERSALE',
                'type' => 'percent',
                'value' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'THANKYOU5',
                'type' => 'percent',
                'value' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BIRTHDAYTREAT',
                'type' => 'fixed',
                'value' => 25000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'VIPDISCOUNT',
                'type' => 'percent',
                'value' => 18,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EASTERBONUS',
                'type' => 'fixed',
                'value' => 60000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BACKTOSCHOOL',
                'type' => 'percent',
                'value' => 28,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MEMBERONLY',
                'type' => 'percent',
                'value' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EXPIREDCODE',
                'type' => 'percent',
                'value' => 10,
                'is_active' => false, // Example of an inactive coupon
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(1),
            ],
            [
                'code' => 'ONEOFFDEAL',
                'type' => 'fixed',
                'value' => 90000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GETFREEITEM',
                'type' => 'fixed',
                'value' => 0, // Could represent a free item or specific offer
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'INACTIVEPROMO',
                'type' => 'fixed',
                'value' => 50000,
                'is_active' => false, // Another inactive coupon
                'created_at' => now()->subYear(),
                'updated_at' => now()->subMonths(6),
            ],
            [
                'code' => 'SHOPNOW10',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LIMITEDTIME',
                'type' => 'fixed',
                'value' => 30000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SAVEBIG',
                'type' => 'percent',
                'value' => 25,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
