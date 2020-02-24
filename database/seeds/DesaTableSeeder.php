<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("desas")->insert([
        ]);
    }
}
