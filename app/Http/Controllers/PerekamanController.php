<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
use DataTables;


class PerekamanController extends Controller
{

    public function index()
    {
        return view("perekaman.index");
    }

    public function json()
    {
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
            "rincianDataBangunans.langit"
        ])->whereNotNull("nop_asal")->get();

        return DataTables::of($spop)
        ->addColumn('action', function($row) {
            return '<a href="/pemutakhiran/'. $row->nop .'" class="btn btn-primary">Lihat</a>';
        })->make(true);
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

        return view("perekaman.create", compact([
            "jenisTanah",
            "statuses",
            "pekerjaans",
            "jenisPenggunaanBangunans",
            "kondisis",
            "konstruksis",
            "ataps",
            "dindings",
            "lantais",
            "langits",
        ]));
    }
}
