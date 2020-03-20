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
use App\Models\Kategori;
use App\Models\Gambar;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use DataTables;
use Auth;

class PerekamanController extends Controller
{

    public function index()
    {
        return view("perekaman.index");
    }

    public function json()
    {
        $spop = new SpopController();
        return $spop->json_spop(1);
    }

    public function create()
    {  
        $spop = new SpopController();
        return $spop->create_spop(null, 1);
    }

    public function store(Request $request)
    {
        $spop = new SpopController();
        return $spop->store_spop($request, 1);
    }

    public function show($uuid)
    {
        $spop = new SpopController();
        return $spop->show_spop($uuid, 1);
    }

    public function edit($uuid)
    {
        $spop = new SpopController();
        return $spop->edit_spop($uuid, 1);
    }

    public function update(Request $request, $uuid)
    {
        $spop = new SpopController();
        return $spop->update_spop($request, $uuid, 1); 
    }

    public function createBangunan($uuid)
    {
        $spop = new SpopController();
        return $spop->create_bangunan_spop($uuid, 1);
    }

    public function storeBangunan(Request $request, $uuid)
    {
        $spop = new SpopController();
        return $spop->store_bangunan_spop($request, $uuid, 1);
    }

   
    public function editBangunan($uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->edit_bangunan_spop($uuid, $uuid_bangunan, 1);
    }

    public function updateBangunan(Request $request, $uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->update_bangunan_spop($request, $uuid, $uuid_bangunan, 1);
    }

    public function showBangunan($uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->show_bangunan_spop( $uuid, $uuid_bangunan, 1);
    }

    public function destroyBangunan($uuid, $uuid_bangunan)
    {
        $spop = new SpopController();
        return $spop->destroy_bangunan_spop($uuid, $uuid_bangunan, 1);
    }
}
