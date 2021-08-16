<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('boards')->truncate();
        DB::table('boards')->insert([
            'id' => 1,
        ]);
        DB::table('boards')->insert([
            'id' => 2,
        ]);
    }
}
