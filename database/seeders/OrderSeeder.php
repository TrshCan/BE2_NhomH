<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Disable mass assignment protection temporarily
        \Illuminate\Database\Eloquent\Model::unguard();

        // Get user IDs (assuming you have a UserSeeder and users exist)
        $userIds = \App\Models\User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found. Please run the UserSeeder first.');
            return;
        }

        // Get product IDs and prices
        $products = Product::whereIn('product_id', [1, 2])->get(['product_name', 'price'])->keyBy('product_id'); //get only product 1 and 2
        if (count($products) < 2) {
            $this->command->error('Not enough products (IDs 1 and 2) found.  Please run the ProductSeeder first, and ensure products with IDs 1 and 2 exist.');
            return;
        }

        // Define possible order statuses
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        // Create 20 orders
        for ($i = 0; $i < 20; $i++) {
            // Randomly select a user ID
            $userId = $userIds[array_rand($userIds)];

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'order_date' => now()->subDays(rand(1, 30)), // Random date within the last 30 days
                'total_amount' => 0, // Initialize to 0, will be updated later
                'status' => $statuses[array_rand($statuses)],
                'shipping_address' => fake()->address(), // Use Faker to generate a random address
            ]);

            // Create 1 or 2 order details for each order
            $numDetails = rand(1, 2);
            $totalAmount = 0; // Keep track of the total for this order

            for ($j = 0; $j < $numDetails; $j++) {
                // Randomly select product ID (1 or 2)
                $productId = ($j % 2) + 1; // Alternate between 1 and 2
                $quantity = rand(1, 5); // Random quantity between 1 and 5

                // Get product price from the fetched products
                $price = $products[$productId]->price;

                // Create the order detail
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalAmount += $price * $quantity;
            }

            // Update the order's total_amount
            $order->update(['total_amount' => $totalAmount]);
        }

        // Re-enable mass assignment protection
        \Illuminate\Database\Eloquent\Model::reguard();
        $this->command->info('Order and OrderDetail seeders executed successfully.');
    }
}
