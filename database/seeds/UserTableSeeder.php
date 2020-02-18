<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            "nip"        => "22222222",
            "instansi"   => "xd",
            "role"       => 1,   
            "password"   => Hash::make("mantapjewa")   
        ]);
    }
}
