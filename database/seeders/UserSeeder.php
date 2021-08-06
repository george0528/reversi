<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'user1',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'user2',
            'password' => Hash::make('password'),
        ]);
    }
}
