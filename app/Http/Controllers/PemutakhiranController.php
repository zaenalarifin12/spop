<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\SpopController;

use App\Models\Status;
use App\Models\Pekerjaan;
use App\Models\JenisTanah;
use App\Models\JenisPenggunaanBangunan;
use App\Models\RincianDataBangunan;
use App\Models\DataLetakObjek;
use App\Models\DataSubjekPajak;
use App\Models\DataTanah;
use App\Models\Kondisi;
use App\Models\Konstruksi;
use App\Models\Atap;
use App\Models\Dinding;
use App\Models\Lantai;
use App\Models\Langit;
use App\Models\Rujukan;
use App\Models\Spop;
use App\Models\Desa;
use App\Models\Gambar;
use App\Models\Kategori;

use Auth;
use DataTables;

class PemutakhiranController extends Controller
{
    /**
     * TODO 
     * menmabhkan edit nomor hp di form edit
     * tambah gambar di semua pemutahiran
     * edit password user
     */

     /**
      * pemutakhiran 0
      * perekaman 1
      */

    public function index()
    {
        return view("pemutakhiran.index");
    }

    public function json()
    {
        $spop = new SpopController();
        return $spop->json_spop(0);
    }

    public function create($uuid)
    {
        $spop = new SpopController();
        return $spop->create_spop($uuid, 0);
    }

    public function store(Request $request, $uuid)
    {
        $spop = new SpopController();
        return $spop->store_spop($request, 0, $uuid);
    }

    public function show($uuid)
    {
        $spop = new SpopController();
        return $spop->show_spop($uuid, 0);
    }

    public function edit($uuid)
    {
        $spop = new SpopController();
        return $spop->edit_spop($uuid, 0);
    }

    public function update(Request $request, $uuid)
    {
        $spop = new SpopController();
        return $spop->update_spop($request, $uuid, 0);
    }

    public function createBangunan($uuid)
    {
        $spop = new SpopController();
        return $spop->create_bangunan_spop($uuid, 0);
    }

    public function storeBangunan(Request $request, $uuid)
    {
        $spop = new SpopController();
        return $spop->store_bangunan_spop($request, $uuid, 0);
    }

    public function editBangunan($uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->edit_bangunan_spop($uuid, $uuid_bangunan, 0);
    }

    public function updateBangunan(Request $request, $uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->update_bangunan_spop($request, $uuid, $uuid_bangunan, 0);
    }

    public function showBangunan($uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->show_bangunan_spop( $uuid, $uuid_bangunan, 0);
    }

    public function destroyBangunan($uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->destroy_bangunan_spop($uuid, $uuid_bangunan, 0);
    }

    public function cari(Request $request)
    {
        // $pt     = urlencode($request->pt);
        // $dtii   = urlencode($request->dtii);
        $kec    = urlencode($request->kec);
        $des    = urlencode($request->des);
        $blok   = urlencode($request->blok);
        $no_urut= urlencode($request->no_urut);
        $kode   = urlencode($request->kode);

        if( empty(trim($kec))  && empty(trim($des))  && empty(trim($blok)) && empty(trim($no_urut)) && empty(trim($kode)) ){
            return view("pemutakhiran.cari");
        }else{
            // 33 18
            $nop            = "33.18.$kec.$des.$blok.$no_urut.$kode";
            $nop_replace    = str_replace(".", "", $nop);
            
            $rujukan    = Rujukan::where("nop", $nop)->first();
            // jika record rujukan ada maka generate uuid
            if($rujukan->uuid == null){
                $uu = Str::random(40) .time();
                $rujukan->update([
                    "uuid" => $uu
                ]);
            }
            if(empty($rujukan)){
                return redirect()->back()->withInput()->with("err", "nop tidak ada");
            }
            $spop = Spop::with([
                "dataLetakObjek",
                "dataSubjekPajak",
                "dataSubjekPajak.status",
                "dataSubjekPajak.pekerjaan",
                // "dataTanah",
                "rincianDataBangunans",
                "rincianDataBangunans.kondisi",
                "rincianDataBangunans.konstruksi",
                "rincianDataBangunans.atap",
                "rincianDataBangunans.lantai",
                "rincianDataBangunans.langit",
                ])->where("nop", $nop_replace)->first();

            // jika spop gak kosong maka masukan spop
            // jika sopo kosong dan maka uji rujukan jika berhasil redirect
            // jika gak ada maka berikan pesan tidak ada

            if (!empty($spop)) {
                return redirect()->action(
                    "PemutakhiranController@show", ["uuid" => $spop->uuid]
                );

            }elseif(empty($spop) && !empty($rujukan)){
                return view("pemutakhiran.cari", compact("rujukan"))->withInput($request->all());
            }else{
                return view("pemutakhiran.cari")->with("msg", "nomor nop tidak ada");
            }
        }
    }
   
}
