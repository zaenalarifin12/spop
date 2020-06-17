<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            "name"       => "zainal",
            "nip"        => "1111111111111111",
            "instansi"   => "xd",
            "role"       => 1,   
            "password"   => Hash::make("11111111")   
        ]);
    }
}
