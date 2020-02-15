<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("kecamatans")->insert([
            [
                "id"    => 1,
                "nama"  => "kecamatan satu"
            ],
            [
                "id"    => 2,
                "nama"  => "kecmatan 2"
            ]
        ]);
    }
}
