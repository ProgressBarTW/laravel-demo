<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::where('search_key', 'fruits')->first();

        Subcategory::create([
            'name' => '本土',
            'search_key' => 'local',
            'order_index' => 1,
            'show_in_list' => true,
            'category_id' => $category->id
        ]);

        Subcategory::create([
            'name' => '進口',
            'search_key' => 'import',
            'order_index' => 1,
            'show_in_list' => true,
            'category_id' => $category->id
        ]);
    }
}
