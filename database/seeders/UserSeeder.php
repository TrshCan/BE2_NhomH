<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'role' => 'admin',
                'email' => 'admin@gmail.com',
                'password'=> Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('users')->insert([
            [
                'name' => 'admin2',
                'role' => 'admin',
                'email' => 'admin2@gmail.com',
                'password'=> Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 10; $i++) {
            DB::table('users')->insert([
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password'=> Hash::make('123456'),
                    'phone' => '0' . $faker->numberBetween(300000000, 999999999),
                    'address' => $faker->address,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

    }
}
