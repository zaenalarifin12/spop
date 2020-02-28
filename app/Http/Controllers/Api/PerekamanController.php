<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
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

use Auth;

class PerekamanController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 1){
            $spops = Spop::with("user")->where("kategori", 1)->paginate(20);
        }else{
            $spops = Spop::with("user")->where("kategori", 1)->where("user_id", Auth::user()->id)->paginate(20);
        }

        return response()->json([
            "data"      => $spops,
            "message"   => "pemutakhiran berhasil ditampilkan"
        ]);            
    }

    public function create()
    {           
        $jenisTanah                 = JenisTanah::get();
        $statuses                   = Status::get();
        $pekerjaans                 = Pekerjaan::get();
        $jenisPenggunaanBangunans   = JenisPenggunaanBangunan::get();
        $kondisis                   = Kondisi::get();
        $konstruksis                = Konstruksi::get();
        $ataps                      = Atap::get();
        $dindings                   = Dinding::get();
        $lantais                    = Lantai::get();
        $langits                    = Langit::get();
        $desas                      = Desa::orderBy("nama", "asc")->get();

        return response()->json([
            "jenisTanah"                => $jenisTanah,
            "statuses"                  => $statuses,
            "pekerjaans"                => $pekerjaans,
            "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
            "kondisis"                  => $kondisis,
            "konstruksis"               => $konstruksis,
            "ataps"                     => $ataps,
            "dindings"                  => $dindings,
            "lantais"                   => $lantais,
            "langits"                   => $lantais,
            "desas"                     => $desas,

            "message"                   => "data pembuatan pemutakhiran ditampilkan",
        ]);    
    }

}
