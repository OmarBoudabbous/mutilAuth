<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'lastname'=>'adminlastname',
            'phone'=>'12345678',
            'role'=>'admin',
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('password'),
        ]);

        User::create([
            'name'=>'Manager',
            'lastname'=>'managerlastname',
            'phone'=>'12345678',
            'role'=>'manager',
            'email'=>'manager@gmail.com',
            'password'=>Hash::make('password'),
        ]);

        User::create([
            'name'=>'Driver',
            'lastname'=>'driverlastname',
            'phone'=>'12345678',
            'role'=>'driver',
            'email'=>'driver@gmail.com',
            'password'=>Hash::make('password'),
        ]);
    }
}
