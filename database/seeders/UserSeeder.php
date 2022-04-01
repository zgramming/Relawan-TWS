<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    private $datas = [
        [
            /// Relawan
            'name'=>"Zeffry Reynando",
            "email"=> "zeffry.reynando@gmail.com",
        ],
        [
            "name"=> "Organisasi Terbaik",
            "email"=> "organisasi@gmail.com",
            "id_type_organization"=> 1,
            'type'=> "organisasi",
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->datas as $value){
            $value['password'] = Hash::make("zeffry");
            User::create($value);
        }
    }
}
