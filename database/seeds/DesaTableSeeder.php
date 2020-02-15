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
            [
                "id"        => 1,
                "nama"      => "desa satu",
                "kecamatan_id" => 1
            ],
            [
                "id"        => 2,
                "nama"      => "desa 2",
                "kecamatan_id" => 2
            ]
        ]);
    }
}
