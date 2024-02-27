<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin seeder
        User::create([
            'name' => 'joudy',
            'email' => 'joudy.nabo@gmail.com',
            'gender' => 1,
            'blood_type'=> 'A+',
            'is_approved'=> 1,
            'is_admin'=> 1,
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(16),
        ]);

        $inc = 0;
        for ($i=0; $i < 100; $i++) { 
            $inc = $inc+1;
            $array = ['A+', 'A-','B+','B-','O+','O-','AB+','AB-'];
            User::create([
                'name' => 'joudy'.$inc,
                'email' => 'joudy.nabo@gmail.com'.$inc,
                'gender' => rand(0,1),
                'blood_type'=> Arr::random($array),
                'is_approved'=> rand(0,1),
                'is_admin'=> rand(0,1),
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
               'remember_token' => Str::random(16),
            ]);
        }
    }
}
