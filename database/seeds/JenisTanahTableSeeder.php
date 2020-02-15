<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTanahTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("jenis_tanahs")->insert([
            [
                "id"    => 1,
                "nama"  => "tanah + bangunan"
            ],
            [
                "id"    => 2,
                "nama"  => "kavling"
            ],
            [
                "id"    => 3,
                "nama"  => "tanah kosong"
            ],
        ]);
    }
}
