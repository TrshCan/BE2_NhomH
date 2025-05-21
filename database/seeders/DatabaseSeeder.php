<?php

namespace Database\Seeders;

use App\Models\User;
<<<<<<< HEAD
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

=======
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


>>>>>>> test_merge_semiver2
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

<<<<<<< HEAD
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
=======
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        try {
            $this->call(CartSeeder::class);
            $this->call(StatusSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(BlogPostSeeder::class);
            $this->call(BrandSeeder::class);
            $this->call(ProductSeeder::class);
            $this->call(OrderSeeder::class);
            $this->call(Images_productSeeder::class);
            $this->call(ProductDetailsSeeder::class);
            $this ->call(PromotionsTableSeeder::class);
            $this->call(PromotionSeeder::class);
            $this->call(CategoriesSeeder::class);
            $this->call(CouponSeeder::class);
        } catch (\Exception $e) {
            $this->command->error("Error seeding database: " . $e->getMessage());
        }
    }
}

>>>>>>> test_merge_semiver2
