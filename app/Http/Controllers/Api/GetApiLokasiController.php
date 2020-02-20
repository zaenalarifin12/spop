<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetApiLokasiController extends Controller
{
    
    public function getProvinsi()
    {
        // initialize
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', "http://dev.farizdotid.com/api/daerahindonesia/provinsi", 
                [
                    "headers" => 
                        ["Accept" => "application/json",
                        "Content-type" => "application/json" ]
                ]);
                    
        // convert json to array
        $hasil = json_decode($res->getBody(), true);

        // mengambil data provinsi
        $provinsi = $hasil["semuaprovinsi"];


        foreach($provinsi as $key => $value)
        {
            DB::table("provinsis")->insert([
                "id"    => $value["id"],
                "nama"  => $value["nama"]
            ]);
        }

        $a = DB::table("provinsis")->get();
        dd("berhasil");

    }

    
    public function getKecamatan()
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request("GET", "http://dev.farizdotid.com/api/daerahindonesia/provinsi/kabupaten/". 3318 . "/kecamatan", 
        [
            "headers" => [
                "Accept"        => "application/json",
                "Content-type"  => "application/json"
            ]
        ]);

        $hasil = json_decode($res->getBody(), true);
        $hasil = $hasil["kecamatans"];
        foreach($hasil as $key => $value){
                DB::table("kecamatans")->insert([
                    "id"            => $value["id"],
                    "nama"          => $value["nama"],
                ]);
            }

        dd("berhasil");
    }

    public function getDesa()
    {
        $client = new \GuzzleHttp\Client();

        $idKab  = DB::table("kecamatans")->select("id")->get();

        foreach($idKab as $key => $value)
        {
            $res = $client->request("GET", "http://dev.farizdotid.com/api/daerahindonesia/provinsi/kabupaten/kecamatan/". $value->id . "/desa", 
            [
                "headers" => [
                    "Accept"        => "application/json",
                    "Content-type"  => "application/json"
                ]
            ]
        );
        
        $hasil = json_decode($res->getBody(), true);
        $hasil = $hasil["desas"];
        
        foreach($hasil as $key => $value){
                DB::table("desas")->insert([
                    "id"            => $value["id"],
                    "nama"          => $value["nama"],
                    "kecamatan_id"   => $value["id_kecamatan"]
                ]);
            }
        // end foreach
        }

        dd("berhasil");
    }
}
