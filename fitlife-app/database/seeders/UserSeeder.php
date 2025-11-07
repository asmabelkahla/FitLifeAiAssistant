<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Member',
            'email' => 'member@member.com',
            'role' => 'member' ,
            'password' => Hash::make('123456789'),
        ]);

        DB::table('users')->insert([
            'name' => 'Coach',
            'email' => 'coach@coach.com',
            'role' => 'coach' ,
            'password' => Hash::make('123456789'),
        ]);
    }
}
