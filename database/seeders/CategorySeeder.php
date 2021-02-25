<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => '水果',
            'search_key' => 'fruits',
            'order_index' => 1,
            'show_in_list' => true
        ]);
    }
}
