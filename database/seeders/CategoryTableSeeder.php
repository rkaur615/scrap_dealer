<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('categories')->truncate();
        $categoryType = [
            'ScrapCategory' => 1,
            'CharitableCategory' => 2,
            'ServiceCategory' => 3,
            'RecyclerCategory' => 4,
        ];
        $categoryData = [
            ['title' => 'Education', 'parent_category_name'=> ''],
            ['title' => 'Health', 'parent_category_name'=> ''],
            ['title' => 'Environment', 'parent_category_name'=> ''],
            ['title' => 'Books', 'parent_category_name'=> 'Education'],
            ['title' => 'Stationery', 'parent_category_name'=> 'Education'],
            ['title' => 'Medicines', 'parent_category_name'=> 'Health'],
            ['title' => 'Doctors', 'parent_category_name'=> 'Health'],
            ['title' => 'Energy sources', 'parent_category_name'=> 'Environment'],
            ['title' => 'protections of nature', 'parent_category_name'=> 'Environment'],
            ['title' => 'Teachers', 'parent_category_name'=> 'Education'],
            ['title' => 'development', 'parent_category_name'=> 'Environment'],
            ['title' => 'furniture', 'parent_category_name'=> ''],
            ['title' => 'Electronics', 'parent_category_name'=> ''],
            ['title' => 'Accessories', 'parent_category_name'=> ''],
            ['title' => 'tv', 'parent_category_name'=> 'Electronics'],
            ['title' => 'mobile', 'parent_category_name'=> 'Electronics'],
            ['title' => 'samsung', 'parent_category_name'=> 'mobile'],
            ['title' => 'LED', 'parent_category_name'=> 'tv'],
            ['title' => 'Table', 'parent_category_name'=> 'furniture'],
            ['title' => 'Bulbs', 'parent_category_name'=> 'Electronics'],
            ['title' => 'Toys', 'parent_category_name'=> 'Accessories'],
            ['title' => 'Blu-ray', 'parent_category_name'=> 'Electronics'],
            ['title' => 'Gifts', 'parent_category_name'=> 'Accessories'],
        ];
    
        foreach($categoryType as $type)
        {
            foreach($categoryData as $cat)
            {
                $parent = Category::firstOrNew(['title'=>$cat['parent_category_name']]);
                Category::factory()->create([
                    'title' => $cat['title'],
                    'parent_id' => $parent->id ? $parent->id : null,
                    'category_type' => $type,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
