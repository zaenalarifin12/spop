<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spop;
use DataTables;

class SpopController extends Controller
{
    public function index()
    {
        return view("spop.index");
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
        ])->get();
        return DataTables::of($spop)
        ->addColumn('action', function($row) {
            return '<a href="/pemutakhiran/'. $row->nop .'" class="btn btn-primary">Lihat</a>';
        })->make(true);
    }
}
