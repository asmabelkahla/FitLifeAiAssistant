<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Met à jour l'utilisateur avec ID 1 ou l'insère s'il n'existe pas
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Coach 1',
                'email' => 'coach1@example.com',
                'password' => Hash::make('password')
            ]
        );
    }
}
