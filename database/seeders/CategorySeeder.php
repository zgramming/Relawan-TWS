<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    private $datas = [
        [
            "name"=> "Category 1",
            "description"=> "Description 1",
        ],
        [
            "name"=> "Category 2",
            "description"=> "Description 2",
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->datas as $value){
            Category::create($value);
        }
    }
}
