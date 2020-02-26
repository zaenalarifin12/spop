<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

use DataTables;

use Auth;

class SpopController extends Controller
{
    public function index()
    {
        return view("spop.index");
    }

    public function json()
    {
        if(Auth::user()->role == 1){
            $spops = Spop::with("user")->get();
        }else{
            $spops = Spop::with("user")->where("user_id",Auth::user()->id)->get();
        }

        // masalah
        // cara membedakan perekaman dan pemutakhiran

        return DataTables::of($spops)
        ->addColumn('action', function($row) {
            if($row->nop == null)
                return '<a href="/perekaman/'. $row->uuid .'" class="btn btn-primary">Lihat</a>';
            else{
                return '<a href="/pemutakhiran/'. $row->uuid .'" class="btn btn-primary">Lihat</a>';
            }
        })->make(true);
    }

}
