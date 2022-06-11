<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Database\Factories\OrderFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'PTH',
            'address' => 'ĐN',
            'phone' => '01234567890',
            'email' => 'thanhhoi@gmail.com',
            'role' => 0,
            'password' => Hash::make(12345678),
            'avatar' => 'https://pbl5-backend.herokuapp.com/avatar/default.png',
            'email_verified_at' => now(),
            'remember_token' => '1234567890',
        ]);
        User::insert([
            'name' => 'stagingSeller',
            'address' => 'VN',
            'phone' => '0111111111',
            'email' => 'seller@Ponzi.com',
            'role' => 2,
            'password' => Hash::make(12345678),
            'avatar' => 'https://pbl5-backend.herokuapp.com/avatar/default.png',
            'email_verified_at' => now(),
            'remember_token' => '1234567890',
        ]);
        User::factory(3)->create(
            ['role' => 2]
        );

        User::factory(16)->create(
            ['role' => 1]
        )->each(function($user) {
            $sanphams = Product::all();
            Order::factory(rand(1, 20))->create([
                'id_user' => $user->id
            ])->each(function($dh) use($sanphams)
            {
                $dh->sanphams()->attach(
                    $sanphams->random(rand(1,$sanphams->count()))->pluck('id')->toArray(),
                    ['amount' => rand(1,10)]
                );
            }
            );

        });
    }
}
