<?php

namespace Database\Seeders;

use App\Models\TypeOrganization;
use Illuminate\Database\Seeder;

class TypeOrganizationSeeder extends Seeder
{
    private $datas = [
        [
          "name"=> "Type Organization 1",
          "description"=> "Type Organization Description 1",
        ],
        [
            "name"=> "Type Organization 2",
            "description"=> "Type Organization Description 2",
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
          TypeOrganization::create($value);
      }
    }
}
