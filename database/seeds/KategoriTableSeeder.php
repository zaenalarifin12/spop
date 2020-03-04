<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("kategoris")->insert([
            [
                "id"    => 1,
                "nama"  => "ktp",
            ],
            [
                "id"    => 2,
                "nama"  => "sppt",
            ],
            [
                "id"    => 3,
                "nama"  => "sertifikat",
            ],
        ]);
    }
}
