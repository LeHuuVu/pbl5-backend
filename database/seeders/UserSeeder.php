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
        // User::insert([
        //     'name' => 'PTH',
        //     'address' => 'ĐN',
        //     'phone' => '01234567890',
        //     'email' => 'thanhhoi@gmail.com',
        //     'role' => 0,
        //     'password' => Hash::make(123456),
        //     'avatar' => '',
        //     'email_verified_at' => now(),
        //     'remember_token' => '1234567890',
        // ]);                
        User::factory(3)->create(
            ['role' => 2]
        );
        User::factory(20)->create()->each(function($user){
            $product = Product::all();
            Order::factory(rand(1,10))->create([
                'id_user' => $user->id
            ])->each(function($order) use ($product, $user){
                $array_id = $product->random(rand(1,$product->count()))->pluck('id')->toArray();
                $total_price = 0;
                foreach($array_id as $id){
                    $total_price += Product::where('id',$id)->first()->price;
                    Review::factory(rand(0,1))->create([
                        'id_user' => $user->id,
                        'id_product' => $id
                    ]);
                }
                $order->product()->attach(
                    $array_id,
                    ['amount' => rand(1,10),
                    'total_price' => $total_price],
                );
            });
        });
    }
}
