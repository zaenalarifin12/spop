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

class PemutakhiranController extends Controller
{
    public function index()
    {
        return Auth::user()->nip;
        die();
        if (Auth::user()->role == 1){
            $spops = Spop::with("user")->where("kategori", 0)->get();
        }else{
            $spops = Spop::with("user")->where("kategori", 0)->where("user_id", Auth::user()->id)->get();
        }

        return response()->json([
            "data"      => $spops,
            "message"   => "pemutakhiran berhasil ditampilkan"
        ]);            
    }
}
