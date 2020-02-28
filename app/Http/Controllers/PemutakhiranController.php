<?php

namespace App\Http\Controllers;

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
use DataTables;

class PemutakhiranController extends Controller
{
    public function index()
    {
        return view("pemutakhiran.index");
    }

    public function json()
    {
        if (Auth::user()->role == 1){
            $spops = Spop::with("user")->where("kategori", 0)->get();
        }else{
            $spops = Spop::with("user")->where("kategori", 0)->where("user_id", Auth::user()->id)->get();
        }

        return DataTables::of($spops)
        ->addColumn('action', function($row) {
            return '<a href="/pemutakhiran/'. $row->uuid .'" class="btn btn-primary">Lihat</a>';
        })->make(true);
    }

    public function create($uuid)
    {
        $rujukan = Rujukan::where("uuid", $uuid)->first();

        if (empty($rujukan)) abort(404);
            // die("nop rujukan tidak ditemukan");

        $spop = Spop::where("nop", str_replace(".", "", $rujukan->nop))->first();
        if(!empty($spop)){
            // jika sudah ada maka langsung ke detail
            return redirect("/pemutakhiran/$spop->uuid");
        }
            
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
        $desas                      = Desa::get()->pluck("nama");

        $my_nop = explode(".", $rujukan->nop);
        
        $wajib_pajak = explode(" ", $rujukan->alamat_wp);

        $objek_pajak = explode(" ", $rujukan->alamat_op);

        $wp_desa       = $wajib_pajak[1];
        $wp_rt         = $wajib_pajak[4];
        $wp_rw         = $wajib_pajak[6];
        $wp_kecamatan  = $wajib_pajak[7];

        $op_desa       = $objek_pajak[1];
        $op_rt         = $objek_pajak[4];
        $op_rw         = $objek_pajak[6];

        return view("pemutakhiran.create", compact([
            "rujukan",
            "my_nop",

            "wp_desa",
            "wp_rt",
            "wp_rw",
            "wp_kecamatan",

            "op_desa",
            "op_rt",
            "op_rw",

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
            "desas"
        ]));
    }

    public function store(Request $request, $uuid)
    {
        switch ($request->input("action")) {
            case "save":
                /**
                 * VALIDASI FORM
                 */
                $this->validate($request, [
                    "dlop_nama_jalan"       => "required",
                    // "dlop_blok"             => "required",
                    "dlop_desa"             => "required",
                    "dlop_rw"               => "required|numeric|digits:2",
                    "dlop_rt"               => "required|numeric|digits:3",
                    "status"                => "required",
                    "pekerjaan"             => "required",
                    "dsp_nama_subjek_pajak" => "required",
                    "dsp_nama_jalan"        => "required",
                    "dsp_kabupaten"         => "required",
                    "dsp_desa"              => "required",
                    "dsp_rw"                => "required|numeric|digits:2",
                    "dsp_rt"                => "required|numeric|digits:3",
                    "dsp_no_ktp"            => "required|numeric|digits:16",
                    "dsp_luas_tanah"        => "required|numeric",
                    "jenis_tanah"           => "required", //  masih kurang validasi 2,3
                ]);

                $rujukan = Rujukan::where("uuid", $uuid)->pluck("nop")->first(); #mencari rujukan di table
                
                if (empty($rujukan)) abort(404);
                    // die("nop belum ada");
                    
                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                if (empty($status)) abort(404);
                    // die("status tidak ada");
                elseif(empty($pekerjaan)) abort(404);
                    // die("pekerjaan tidak ada");
                
                
                $uu = Str::random(40) .time();
                if(Spop::where("uuid", $uu)->first() != null)
                    $uu = Str::random(40) .time();
                
                $desa = Desa::where("nama", "$request->dlop_desa")->first();
                if(empty($desa))
                    return redirect()->back()->withInput()->with("msg", "Desa tidak ditemukan didaerah pati");
                    
                $spop           = new Spop();
                $spop->uuid     = $uu;
                $spop->nop      = (str_replace(".", "", $rujukan));
                $spop->kategori = 0;
                $spop->user_id  = Auth::user()->id;
                $spop->save();

                $DataLetakObjekPajak = DataLetakObjek::create([
                    "nama_jalan"        => $request->dlop_nama_jalan,
                    "desa_id"           => $desa->id,
                    "blok_kav"          => $request->dlop_blok,
                    "rw"                => $request->dlop_rw,
                    "rt"                => $request->dlop_rt,
                    "spop_id"           => $spop->id,
                ]);
                
                $DataSubjekPajak = DataSubjekPajak::create([
                    "nama_subjek_pajak" => $request->dsp_nama_subjek_pajak,
                    "nama_jalan"        => $request->dsp_nama_jalan,
                    "rt"                => $request->dsp_rt,
                    "rw"                => $request->dsp_rw,
                    "nomor_ktp"         => $request->dsp_no_ktp,
                    "status_id"         => $status,
                    "pekerjaan_id"      => $pekerjaan,
                    "desa"              => $request->dsp_desa,
                    "kabupaten"         => $request->dsp_kabupaten,
                    "spop_id"           => $spop->id
                ]);
                
                $spop->update([
                    "data_letak_objek_id" => $DataLetakObjekPajak->id,
                    "data_subjek_pajak_id" => $DataSubjekPajak->id
                ]);

                if($request->jenis_tanah == 1){
                    die("jenis tanah yang di pilih harus 1");
                }elseif($request->jenis_tanah == 2 || $request->jenis_tanah == 3){

                    $dataTanah = DataTanah::create([
                        "luas_tanah"        => $request->dsp_luas_tanah,
                        "jenis_tanah_id"    => $request->jenis_tanah,
                        "spop_id"           => $spop->id,
                    ]);

                    $spop->update([
                        "data_letak_objek_id"   => $DataLetakObjekPajak->id,
                        "data_subjek_pajak_id"  => $DataSubjekPajak->id,
                        "data_tanah_id"         => $dataTanah->id,
                    ]);
                    $dataTanah->update([
                        "jenis_tanah_id"    => $request->jenis_tanah
                    ]);

                     // jika nop ngga kosong
                     return redirect("/pemutakhiran/" . $spop->uuid);
                    // redirect to add new
                }else{
                    die("jenis tanah yang di pilih tidak ada");
                }
                break;
            case "tambah":    
                /**
                 * VALIDASI FORM
                 */
                $this->validate($request, [
                    "dlop_nama_jalan"       => "required",
                    // "dlop_blok"             => "required",
                    "dlop_desa"             => "required",
                    "dlop_rw"               => "required|numeric|digits:2",
                    "dlop_rt"               => "required|numeric|digits:3",
                    "status"                => "required",
                    "pekerjaan"             => "required",
                    "dsp_nama_subjek_pajak" => "required",
                    "dsp_nama_jalan"        => "required",
                    "dsp_kabupaten"         => "required",
                    "dsp_desa"              => "required",
                    "dsp_rw"                => "required|numeric|digits:2",
                    "dsp_rt"                => "required|numeric|digits:3",
                    "dsp_no_ktp"            => "required|numeric|digits:16",
                    "dsp_luas_tanah"        => "required|numeric",
                    "jenis_tanah"           => "required|in:1", //  masih kurang validasi 1,2,3
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required|numeric|min:0",
                    "jumlah_lantai"         => "required|numeric|min:0",
                    "tahun_dibangun"        => "required|numeric|digits:4",
                    "tahun_renovasi"        => "required|numeric|digits:4",
                    "jumlah_bangunan"       => "required|numeric|min:0",
                    "daya"                  => "required|numeric|min:0",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);

                $rujukan = Rujukan::where("uuid", $uuid)->first(); #mencari rujukan di table
                if (empty($rujukan))
                    die("nop belum ada");

                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                if (empty($status))
                    die("status tidak ada");
                elseif(empty($pekerjaan))
                    die("pekerjaan tidak ada");
                
                $uu = Str::random(40) .time();
                if(Spop::where("uuid", $uu)->first() != null)
                    $uu = $uu.time();
                        
                $spop           = new Spop();
                $spop->uuid     = $uu;
                $spop->nop      = str_replace(".", "", $rujukan->nop);
                $spop->kategori = 0;
                $spop->user_id  = Auth::user()->id;
                $spop->save();

                $desa = Desa::where("nama", "$request->dlop_desa")->first();
                
                $DataLetakObjekPajak = DataLetakObjek::create([
                    "nama_jalan"        => $request->dlop_nama_jalan,
                    "desa_id"           => $desa->id,
                    "blok_kav"          => $request->dlop_blok,
                    "rw"                => $request->dlop_rw,
                    "rt"                => $request->dlop_rt,
                    "spop_id"           => $spop->id,
                ]);
                
                $DataSubjekPajak = DataSubjekPajak::create([
                    "nama_subjek_pajak" => $request->dsp_nama_subjek_pajak,
                    "nama_jalan"        => $request->dsp_nama_jalan,
                    "rt"                => $request->dsp_rt,
                    "rw"                => $request->dsp_rw,
                    "nomor_ktp"         => $request->dsp_no_ktp,
                    "status_id"         => $status,
                    "pekerjaan_id"      => $pekerjaan,
                    "desa"              => $request->dsp_desa,
                    "kabupaten"         => $request->dsp_kabupaten,
                    "spop_id"           => $spop->id
                ]);
                
                $spop->update([
                    "data_letak_objek_id" => $DataLetakObjekPajak->id,
                    "data_subjek_pajak_id" => $DataSubjekPajak->id
                ]);

                if($request->jenis_tanah != 1){
                    die("jenis tanah yang di pilih harus 1");
                }elseif($request->jenis_tanah == 1){
                    $kondisi                    = Kondisi::where("id", $request->kondisi)->pluck("id")->first();
                    $jenisPenggunaanBangunan    = JenisPenggunaanBangunan::where("id", $request->penggunaan)->pluck("id")->first();
                    $konstruksi                 = Konstruksi::where("id", $request->konstruksi)->pluck("id")->first();
                    $atap                       = Atap::where("id", $request->atap)->pluck("id")->first();
                    $dinding                    = Dinding::where("id", $request->dinding)->pluck("id")->first();
                    $lantai                     = Lantai::where("id", $request->lantai)->pluck("id")->first();
                    $langit                     = Langit::where("id", $request->langit)->pluck("id")->first();
                    
                    $dataTanah = DataTanah::create([
                        "luas_tanah"        => $request->dsp_luas_tanah,
                        "jenis_tanah_id"    => $request->jenis_tanah,
                        "spop_id"           => $spop->id,
                    ]);

                    $random = Str::random(40);
                    if(RincianDataBangunan::where("uuid", $random)->first() != null){
                        $random = Str::random(40) .time();
                    }    

                    $RincianDataBangunan = RincianDataBangunan::create([
                        "uuid"                          => $random,
                        "jenis_penggunaan_bangunan_id"  => $jenisPenggunaanBangunan,
                        "luas_bangunan"                 => $request->luas_bangunan,
                        "jumlah_lantai"                 => $request->jumlah_lantai,
                        "tahun_dibangun"                => $request->tahun_dibangun,
                        "tahun_renovasi"                => $request->tahun_renovasi,
                        "daya_listrik"                  => $request->daya,
                        "jumlah_bangunan"               => $request->jumlah_bangunan,
                        "kondisi_id"                    => $kondisi,
                        "konstruksi_id"                 => $konstruksi,
                        "atap_id"                       => $atap,
                        "dinding_id"                    => $dinding,
                        "lantai_id"                     => $lantai,
                        "langit_id"                     => $langit,
                        "spop_id"                       => $spop->id
                    ]);

                    $spop->update([
                        "data_letak_objek_id"   => $DataLetakObjekPajak->id,
                        "data_subjek_pajak_id"  => $DataSubjekPajak->id,
                        "data_tanah_id"         => $dataTanah->id,
                    ]);
                    $dataTanah->update([
                        "jenis_tanah_id"    => $request->jenis_tanah
                    ]);

                     // jika nop ngga kosong
                     return redirect("/pemutakhiran/" . $spop->uuid . "/bangunan/create");
                     // redirect to bangunan new

                }else
                    die("jenis tanah tidak ada");        

                break;
            default:
                die("tidak ada action");
        }
    }

    public function createBangunan($uuid)
    {
        $uuid;
        $spop = Spop::with("rincianDataBangunans")->where("uuid", $uuid)->first();
        
        $jumlah_bangunan = $spop->rincianDataBangunans->count();
        if($jumlah_bangunan != 0){
            $value = $jumlah_bangunan+1;    
        }else{
            $value = 1;  
        }

        $jenisPenggunaanBangunans   = JenisPenggunaanBangunan::get();
        $kondisis                   = Kondisi::get();
        $konstruksis                = Konstruksi::get();
        $ataps                      = Atap::get();
        $dindings                   = Dinding::get();
        $lantais                    = Lantai::get();
        $langits                    = Langit::get();

        return view("pemutakhiran.createBangunan", compact([
            "jenisPenggunaanBangunans",
            "kondisis",
            "konstruksis",
            "ataps",
            "dindings",
            "lantais",
            "langits",
            "uuid"
        ]))->with("urutan_bangunan", $value);
    }

    public function storeBangunan(Request $request, $uuid)
    {

        switch ($request->input("action")) {
            case "save":

                /**
                 * VALIDASI FORM
                 */
                $this->validate($request, [
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required|numeric|min:0",
                    "jumlah_lantai"         => "required|numeric|min:0",
                    "tahun_dibangun"        => "required|numeric|digits:4",
                    "tahun_renovasi"        => "required|numeric|digits:4",
                    "jumlah_bangunan"       => "required|numeric|min:0",
                    "daya"                  => "required|numeric|min:0",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);
                
                $spop  = Spop::where("uuid", $uuid)->first();

                $kondisi                    = Kondisi::where("id", $request->kondisi)->pluck("id")->first();
                $jenisPenggunaanBangunan    = JenisPenggunaanBangunan::where("id", $request->penggunaan)->pluck("id")->first();
                $konstruksi                 = Konstruksi::where("id", $request->konstruksi)->pluck("id")->first();
                $atap                       = Atap::where("id", $request->atap)->pluck("id")->first();
                $dinding                    = Dinding::where("id", $request->dinding)->pluck("id")->first();
                $lantai                     = Lantai::where("id", $request->lantai)->pluck("id")->first();
                $langit                     = Langit::where("id", $request->langit)->pluck("id")->first();

                if (empty($spop))
                    die("nop belum ada");

                if (empty($kondisi))
                    die("Kondisi tidak ada");

                if (empty($jenisPenggunaanBangunan))
                    die("jenisPenggunaanBangunan tidak ada");
                
                if (empty($dinding))
                    die("dinding tidak ada");
                
                if (empty($lantai))
                    die("lantai tidak ada");
                
                if (empty($langit))
                    die("langit tidak ada");
                        
                $random = Str::random(40);
                if(RincianDataBangunan::where("uuid", $random)->first() != null){
                    $random = Str::random(40) .time();
                }

                $RincianDataBangunan = RincianDataBangunan::create([
                    "uuid"                          => $random,
                    "jenis_penggunaan_bangunan_id"  => $jenisPenggunaanBangunan,
                    "luas_bangunan"                 => $request->luas_bangunan,
                    "jumlah_lantai"                 => $request->jumlah_lantai,
                    "tahun_dibangun"                => $request->tahun_dibangun,
                    "tahun_renovasi"                => $request->tahun_renovasi,
                    "daya_listrik"                  => $request->daya,
                    "jumlah_bangunan"               => $request->jumlah_bangunan,
                    "kondisi_id"                    => $kondisi,
                    "konstruksi_id"                 => $konstruksi,
                    "atap_id"                       => $atap,
                    "dinding_id"                    => $dinding,
                    "lantai_id"                     => $lantai,
                    "langit_id"                     => $langit,
                    "spop_id"                       => $spop->id
                ]);

                // redirect to add new
                session()->forget('urutan_bangunan'); // menghapus session
                return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "bangunan berhasil ditambahkan");

                break;
            case "tambah":     
                /**
                 * VALIDASI FORM
                 */
                $this->validate($request, [
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required|numeric|min:0",
                    "jumlah_lantai"         => "required|numeric|min:0",
                    "tahun_dibangun"        => "required|numeric|digits:4",
                    "tahun_renovasi"        => "required|numeric|digits:4",
                    "jumlah_bangunan"       => "required|numeric|min:0",
                    "daya"                  => "required|numeric|min:0",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);
                
                $spop  = Spop::where("uuid", $uuid)->first();

                $kondisi                    = Kondisi::where("id", $request->kondisi)->pluck("id")->first();
                $jenisPenggunaanBangunan    = JenisPenggunaanBangunan::where("id", $request->penggunaan)->pluck("id")->first();
                $konstruksi                 = Konstruksi::where("id", $request->konstruksi)->pluck("id")->first();
                $atap                       = Atap::where("id", $request->atap)->pluck("id")->first();
                $dinding                    = Dinding::where("id", $request->dinding)->pluck("id")->first();
                $lantai                     = Lantai::where("id", $request->lantai)->pluck("id")->first();
                $langit                     = Langit::where("id", $request->langit)->pluck("id")->first();

                if (empty($spop))
                    die("nop belum ada");

                if (empty($kondisi))
                    die("Kondisi tidak ada");

                if (empty($jenisPenggunaanBangunan))
                    die("jenisPenggunaanBangunan tidak ada");
                
                if (empty($dinding))
                    die("dinding tidak ada");
                
                if (empty($lantai))
                    die("lantai tidak ada");
                
                if (empty($langit))
                    die("langit tidak ada");
                        
                $random = Str::random(40);
                if(RincianDataBangunan::where("uuid", $random)->first() != null){
                    $random = Str::random(40) .time();
                }

                $RincianDataBangunan = RincianDataBangunan::create([
                    "uuid"                          => $random,
                    "jenis_penggunaan_bangunan_id"  => $jenisPenggunaanBangunan,
                    "luas_bangunan"                 => $request->luas_bangunan,
                    "jumlah_lantai"                 => $request->jumlah_lantai,
                    "tahun_dibangun"                => $request->tahun_dibangun,
                    "tahun_renovasi"                => $request->tahun_renovasi,
                    "daya_listrik"                  => $request->daya,
                    "jumlah_bangunan"               => $request->jumlah_bangunan,
                    "kondisi_id"                    => $kondisi,
                    "konstruksi_id"                 => $konstruksi,
                    "atap_id"                       => $atap,
                    "dinding_id"                    => $dinding,
                    "lantai_id"                     => $lantai,
                    "langit_id"                     => $langit,
                    "spop_id"                       => $spop->id
                ]);
                
                $value = session('urutan_bangunan');
                $value++;
                session(["urutan_bangunan" => $value]);
                // jika nop ngga kosong
                return redirect("/pemutakhiran/" . $spop->uuid . "/bangunan/create");
                // redirect to bangunan new
                break;
            default:
                die("tidak ada action");
        }
    }

    public function show($uuid)
    {
        $spop = Spop::with([
            "dataLetakObjek",
            "dataLetakObjek.desa",
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
            ])->where("uuid", $uuid)->first();

        $statuses                   = Status::get();
        $pekerjaans                 = Pekerjaan::get();
        $jenisPenggunaanBangunans   = JenisPenggunaanBangunan::get();
        $kondisis                   = Kondisi::get();
        $konstruksis                = Konstruksi::get();
        $ataps                      = Atap::get();
        $dindings                   = Dinding::get();
        $lantais                    = Lantai::get();
        $langits                    = Langit::get();
        $jenisTanah                 = JenisTanah::get();

        return view("pemutakhiran.show", compact([
            "spop",

            "statuses",
            "pekerjaans",
            "jenisPenggunaanBangunans",
            "kondisis",
            "konstruksis",
            "ataps",
            "dindings",
            "lantais",
            "langits",
            "jenisTanah"
        ]));
    }

    public function edit($uuid)
    {
        $spop = Spop::with([
            "dataLetakObjek",
            "dataLetakObjek.desa",
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
            ])->where("uuid", $uuid)->first();

        if(empty($spop)){
            abort(404);
        }

        $desas                      = Desa::get()->pluck("nama");
        $statuses                   = Status::get();
        $pekerjaans                 = Pekerjaan::get();
        $jenisPenggunaanBangunans   = JenisPenggunaanBangunan::get();
        $kondisis                   = Kondisi::get();
        $konstruksis                = Konstruksi::get();
        $ataps                      = Atap::get();
        $dindings                   = Dinding::get();
        $lantais                    = Lantai::get();
        $langits                    = Langit::get();
        $jenisTanah                 = JenisTanah::get();

        return view("pemutakhiran.edit", compact([
            "spop",
            "desas",

            "statuses",
            "pekerjaans",
            "jenisPenggunaanBangunans",
            "kondisis",
            "konstruksis",
            "ataps",
            "dindings",
            "lantais",
            "langits",
            "jenisTanah"
        ]));
    }

    public function update(Request $request, $uuid)
    {
        /**
         * VALIDASI FORM
         */
        $this->validate($request, [
            "dlop_nama_jalan"       => "required",
            // "dlop_blok"             => "required",
            "dlop_desa"             => "required",
            "dlop_rw"               => "required|numeric|digits:2",
            "dlop_rt"               => "required|numeric|digits:3",
            "status"                => "required",
            "pekerjaan"             => "required",
            "dsp_nama_subjek_pajak" => "required",
            "dsp_nama_jalan"        => "required",
            "dsp_kabupaten"         => "required",
            "dsp_desa"              => "required",
            "dsp_rw"                => "required|numeric|digits:2",
            "dsp_rt"                => "required|numeric|digits:3",
            "dsp_no_ktp"            => "required|numeric|digits:16",
            "dsp_luas_tanah"        => "required|numeric",
            "jenis_tanah"           => "required",
        ]);

        $spop = Spop::where("uuid", $uuid)->first(); #mencari rujukan di table
        if (empty($spop))
            die("Nop tidak ada");

        $status     = Status::where("id", $request->status)->pluck("id")->first();
        $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();

        if (empty($status))
            die("status tidak ada");
        elseif(empty($pekerjaan))
            die("pekerjaan tidak ada");

        $desa = Desa::where("nama", "$request->dlop_desa")->first();
        $DataLetakObjekPajak = DataLetakObjek::where("spop_id", $spop->id)
            ->update([
                "nama_jalan"        => $request->dlop_nama_jalan,
                "desa_id"           => $desa->id,
                "blok_kav"          => $request->dlop_blok,
                "rw"                => $request->dlop_rw,
                "rt"                => $request->dlop_rt,
            ]);
        
        $DataSubjekPajak = DataSubjekPajak::where("spop_id", $spop->id)
            ->update([
                "nama_subjek_pajak" => $request->dsp_nama_subjek_pajak,
                "nama_jalan"        => $request->dsp_nama_jalan,
                "rt"                => $request->dsp_rt,
                "rw"                => $request->dsp_rw,
                "nomor_ktp"         => $request->dsp_no_ktp,
                "status_id"         => $status,
                "pekerjaan_id"      => $pekerjaan,
                "desa"              => $request->dsp_desa,
                "kabupaten"         => $request->dsp_kabupaten,
            ]);

        if($request->jenis_tanah == 2 || $request->jenis_tanah == 3 || $request->jenis_tanah == 1){

            $dataTanah = DataTanah::where("spop_id", $spop->id)->update([
                "luas_tanah"        => $request->dsp_luas_tanah,
                "jenis_tanah_id"    => $request->jenis_tanah,
            ]);

                // jika nop ngga kosong
                return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "data pemilik telah berhasil diubah");
            // redirect to add new
        }else{
            die("jenis tanah yang di pilih tidak ada");
        }
    }

    public function editBangunan($uuid, $uuid_bangunan)
    {
        $spop = Spop::where("uuid", $uuid)->first();
        $rincianDataBangunan    = RincianDataBangunan::with([
            "spop",
            "jenisPenggunaanBangunan",
            "kondisi",
            "konstruksi",
            "atap",
            "dinding",
            "lantai",
            "langit"
        ])->where([
            ["uuid"       ,$uuid_bangunan],
            ["spop_id"  ,$spop->id]
            ])->first();

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

        return view("pemutakhiran.bangunan.edit", compact([
            "rincianDataBangunan",
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
            "spop"
            ]));
    }

    public function updateBangunan(Request $request, $uuid, $uuid_bangunan)
    {
        $this->validate($request, [
            // BANGUNAN
            "penggunaan"            => "required",
            "luas_bangunan"         => "required|numeric|min:0",
            "jumlah_lantai"         => "required|numeric|min:0",
            "tahun_dibangun"        => "required|numeric|digits:4",
            "tahun_renovasi"        => "required|numeric|digits:4",
            "jumlah_bangunan"       => "required|numeric|min:0",
            "daya"                  => "required|numeric|min:0",
            "kondisi"               => "required",
            "konstruksi"            => "required",
            "atap"                  => "required",
            "dinding"               => "required",
            "lantai"                => "required",
            "langit"                => "required",
        ]);

        $spop  = Spop::where("uuid", $uuid)->first();
        $rincianDataBangunan    = RincianDataBangunan::where([
            ["uuid"     ,$uuid_bangunan],
            ["spop_id"  ,$spop->id]
        ])->first();

        $kondisi                    = Kondisi::where("id", $request->kondisi)->pluck("id")->first();
        $jenisPenggunaanBangunan    = JenisPenggunaanBangunan::where("id", $request->penggunaan)->pluck("id")->first();
        $konstruksi                 = Konstruksi::where("id", $request->konstruksi)->pluck("id")->first();
        $atap                       = Atap::where("id", $request->atap)->pluck("id")->first();
        $dinding                    = Dinding::where("id", $request->dinding)->pluck("id")->first();
        $lantai                     = Lantai::where("id", $request->lantai)->pluck("id")->first();
        $langit                     = Langit::where("id", $request->langit)->pluck("id")->first();

        $rincianDataBangunan->update([
            "jenis_penggunaan_bangunan_id"  => $jenisPenggunaanBangunan,
            "luas_bangunan"                 => $request->luas_bangunan,
            "jumlah_lantai"                 => $request->jumlah_lantai,
            "tahun_dibangun"                => $request->tahun_dibangun,
            "tahun_renovasi"                => $request->tahun_renovasi,
            "daya_listrik"                  => $request->daya,
            "jumlah_bangunan"               => $request->jumlah_bangunan,
            "kondisi_id"                    => $kondisi,
            "konstruksi_id"                 => $konstruksi,
            "atap_id"                       => $atap,
            "dinding_id"                    => $dinding,
            "lantai_id"                     => $lantai,
            "langit_id"                     => $langit,
        ]);

        return redirect("/pemutakhiran/$spop->uuid/bangunan/$rincianDataBangunan->uuid");
    }

    public function showBangunan($uuid, $uuid_bangunan)
    {
        $spop = Spop::where("uuid", $uuid)->first();
        $rincianDataBangunan    = RincianDataBangunan::with([
            "spop",
            "jenisPenggunaanBangunan",
            "kondisi",
            "konstruksi",
            "atap",
            "dinding",
            "lantai",
            "langit"
        ])->where([
            ["uuid"       ,$uuid_bangunan],
            ["spop_id"  ,$spop->id]
            ])->first();

            // validasi jika kosong
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

        return view("pemutakhiran.bangunan.show", compact([
            "rincianDataBangunan",
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
            "spop"
        ]));

    }

    public function destroyBangunan($uuid, $uuid_bangunan)
    {
        $spop = Spop::where("uuid", $uuid)->first();
        $rincianDataBangunan = RincianDataBangunan::where([
            ["uuid", $uuid_bangunan],
            ["spop_id", $spop->id]
        ])->first();

        if(empty($rincianDataBangunan)){
            abort(404);
        }
        $rincianDataBangunan->delete();
        
        return redirect("/pemutakhiran/".$spop->uuid)->with("msg", "data bangunan berhasil di hapus");
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
                    "PemutakhiranController@show", ["nop" => $nop_replace]
                );

            }elseif(empty($spop) && !empty($rujukan)){
                return view("pemutakhiran.cari", compact("rujukan"))->withInput($request->all());
            }else{
                return view("pemutakhiran.cari")->with("msg", "nomor nop tidak ada");
            }
        }
    }

}
