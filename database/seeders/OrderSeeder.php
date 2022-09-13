<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        User::factory(16)->create(
            ['role' => 1]
        )->each(function($user) {
            $sanphams = Product::all();
            Order::factory(rand(1, 20))->create([
                'id_user' => $user->id
            ])->each(function($dh) use($sanphams)
            {
                $dh->Product()->attach(
                    $sanphams->random(
                        rand(1,$sanphams->count())
                        )->pluck('id')->toArray(),
                    ['amount' => rand(1,10)]
                );
            }
            );

        });

    }
}
