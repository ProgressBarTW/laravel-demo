<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            'name' => '水果王',
            'search_key' => 'fruitking',
            'order_index' => 1,
            'show_in_list' => true
        ]);

        Brand::create([
            'name' => '超好吃',
            'search_key' => 'supergood',
            'order_index' => 2,
            'show_in_list' => true
        ]);
    }
}
