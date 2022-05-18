<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::insert([
            'id_user' => 2,
            'name' => 'Toshiba'
        ]);

        Company::insert([
            'id_user' => 3,
            'name' => 'VNG'
        ]);

        Company::insert([
            'id_user' => 4,
            'name' => 'Eat'
        ]);
    }
}
