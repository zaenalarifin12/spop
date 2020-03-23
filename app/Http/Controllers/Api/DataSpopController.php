<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
use App\Models\Desa;
use App\Models\Gambar;
use App\Models\Kategori;

class DataSpopController extends Controller
{
    public function Status()
    {
        $status = Status::get();

        return response()->json([
            "value" => 1,
            "data"  => $status
        ]);
    }

    public function Pekerjaan()
    {
        $Pekerjaan = Pekerjaan::get();

        return response()->json([
            "value" => 1,
            "data"  => $Pekerjaan
        ]);
    }

    public function JenisPenggunaanBangunan()
    {
        $JenisPenggunaanBangunan = JenisPenggunaanBangunan::get();

        return response()->json([
            "value" => 1,
            "data"  => $JenisPenggunaanBangunan
        ]);
    }

    public function RincianDataBangunan()
    {
        $RincianDataBangunan = RincianDataBangunan::get();

        return response()->json([
            "value" => 1,
            "data"  => $RincianDataBangunan
        ]);
    }

    public function Kondisi()
    {
        $Kondisi = Kondisi::get();

        return response()->json([
            "value" => 1,
            "data"  => $Kondisi
        ]);
    }

    public function Konstruksi()
    {
        $Konstruksi = Konstruksi::get();

        return response()->json([
            "value" => 1,
            "data"  => $Konstruksi
        ]);
    }

    public function Atap()
    {
        $Atap = Atap::get();

        return response()->json([
            "value" => 1,
            "data"  => $Atap
        ]);
    }

    public function Dinding()
    {
        $Dinding = Dinding::get();

        return response()->json([
            "value" => 1,
            "data"  => $Dinding
        ]);
    }

    public function Lantai()
    {
        $Atap = Atap::Lantai();

        return response()->json([
            "value" => 1,
            "data"  => $Lantai
        ]);
    }

    public function Langit()
    {
        $Langit = Atap::get();

        return response()->json([
            "value" => 1,
            "data"  => $Langit
        ]);
    }

    public function Desa()
    {
        $Desa = Desa::get();

        return response()->json([
            "value" => 1,
            "data"  => $Desa
        ]);
    }

    public function Kategori()
    {
        $Langit = Atap::get();

        return response()->json([
            "value" => 1,
            "data"  => $Langit
        ]);
    }


}
