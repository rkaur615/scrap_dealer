<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketCat extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            ['name' => 'Payment'],
            ['name' => 'Account'],
        ];

        foreach ($cats as $cat) {
            $catExist = TicketCategory::where('name', $cat['name'])->first();
            if ($catExist === null) {
                TicketCategory::create($cat);
            }
        }
    }
}
