<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Dummy Users Array
        $users = [
            [
                'name'=>'Chaitali karpe',
                'email'=>'chaitali@gmail.com',
                'password'=> Hash::make('chaitali1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Komal',
                'email'=>'komal@gmail.com',
                'password'=> Hash::make('komal1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Harsh',
                'email'=>'harsh@gmail.com',
                'password'=> Hash::make('harsh1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Sachin',
                'email'=>'sachin@gmail.com',
                'password'=> Hash::make('sachin1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
            ,
            [
                'name'=>'Bilal',
                'email'=>'bilal@gmail.com',
                'password'=> Hash::make('bilal1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Vishal',
                'email'=>'vishal@gmail.com',
                'password'=> Hash::make('vishal1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Jay',
                'email'=>'jay@gmail.com',
                'password'=> Hash::make('jay1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Neha',
                'email'=>'neha@gmail.com',
                'password'=> Hash::make('neha1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Prapti',
                'email'=>'prapti@gmail.com',
                'password'=> Hash::make('prapti1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Pooja',
                'email'=>'pooja@gmail.com',
                'password'=> Hash::make('pooja1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        // Looping and Inserting Array's Users into User Table
        foreach($users as $user){
            User::create($user);
        }
    }
}
