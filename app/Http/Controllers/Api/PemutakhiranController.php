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
use App\Models\Gambar;
use App\Models\Kategori;

use Auth;
use DataTables;

class PemutakhiranController extends Controller
{
    /**
     * pemutakhiran 0
     * perekaman 1
     */
    public function index()
    {
        $spops = Spop::with("user")->where("kategori", 0)->where("user_id", Auth::user()->id)->get();

        if(empty($spops)){
            return response()->json([
                "value"     => 1,
                "message"   => "data pemutakhiran kosong",
            ]);    
        }

        return response()->json([
            "value"     => 1,
            "message"   => "data pemutakhiran",
            "data"      => $spops
        ]);
    }

    public function create($uuid)
    {
        $rujukan = Rujukan::where("uuid", $uuid)->first();

        if (empty($rujukan)) abort(404);
            // die("nop rujukan tidak ditemukan");

        $spop = Spop::where("nop", str_replace(".", "", $rujukan->nop))->first();

        if(!empty($spop)){
            // jika sudah ada maka langsung ke detail
            return response()->json([
                "value" => 2,
                "message" => "data untuk pembuatan pemutakhiran berhasil ditampilkan",
                "data"  => $spop
            ]);
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
        $desas                      = Desa::orderBy("nama", "ASC")->get()->pluck("nama");
        $kategori                   = Kategori::get();

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

        return response()->json([
            "value" => 1,
            "message" => "data untuk pembuatan pemutakhiran berhasil ditampilkan",
            "data" => [
                $rujukan,
                $my_nop,

                $wp_desa,
                $wp_rt,
                $wp_rw,
                $wp_kecamatan,

                $op_desa,
                $op_rt,
                $op_rw,

                $jenisTanah,
                $statuses,
                $pekerjaans,
                $jenisPenggunaanBangunans,
                $kondisis,
                $konstruksis,
                $ataps,
                $dindings,
                $lantais,
                $langits,
                $desas,
                $kategori
            ]
        ]); 
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
                    // dsp_no_hp
                    "dsp_luas_tanah"        => "required|numeric",
                    "jenis_tanah"           => "required", //  masih kurang validasi 2,3
                ]);

                $rujukan = Rujukan::where("uuid", $uuid)->pluck("nop")->first(); #mencari rujukan di table
                
                if (empty($rujukan)) abort(404);
                    // die("nop belum ada");
                    
                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                $desa       = Desa::where("nama", "$request->dlop_desa")->first();

                if (empty($status)) {
                    return response()->json([
                        "value"     => 0,
                        "message"   => "Status tidak ada"
                    ]);
                }
                elseif(empty($pekerjaan)){
                    return response()->json([
                        "value"     => 0,
                        "message"   => "Pekerjaan tidak ada"
                    ]);
                }
                elseif(empty($desa)){
                    return response()->json([
                        "value"     => 0,
                        "message"   => "Desa tidak ditemukan didaerah pati"
                    ]);
                }

                $uu = Str::random(40) .time();
                if(Spop::where("uuid", $uu)->first() != null)
                    $uu = Str::random(40) .time();
                
                if($request->jenis_tanah == 1){
                    return response()->json([
                        "value"     => 0,
                        "message"   => "jenis tanah yang di pilih harus tanah dan bangunan"
                    ]);
                }elseif($request->jenis_tanah == 2 || $request->jenis_tanah == 3){

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
                        "nomor_hp"          => $request->dsp_no_hp,
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
                    
                    if($request->has("gambar")){
                        /**
                         * untuk pengujian keberadaan kategori
                         */
                        foreach($request->gambar as $key => $value){
                            $kategori = Kategori::where("id", $key)->first();
                            if($kategori->id == null){
                                return response()->json([
                                    "value"     => 0,
                                    "message"   => "Kategori gambar tidak ada"
                                ]);
                            }
                        }
                        /**
                         * insert @image
                         */
                        foreach($request->gambar as $image => $valueImage){
                            foreach($valueImage as $key => $gambar){

                                $name = Str::random(20).time(). "." .$gambar->getClientOriginalExtension();

                                $gambar->storeAs(
                                    'public/data_spop', $name
                                );

                                Gambar::create([
                                    "nama"          => $name,
                                    "spop_id"       => $spop->id,
                                    "kategori_id"   => $image
                                ]);
                            }
                        }
                    }

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
                     return response()->json([
                        "value"    => 0,
                        "data"      => $spop->uuid
                     ]);
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
                    // "dsp_no_hp"
                    "dsp_luas_tanah"        => "required|numeric",
                    "jenis_tanah"           => "required|in:1", //  masih kurang validasi 1,2,3
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required|numeric|min:0",
                    "jumlah_lantai"         => "required|numeric|min:0",
                    "tahun_dibangun"        => "required|numeric|digits:4",
                    "tahun_renovasi"        => "required|numeric|digits:4",
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
                    return redirect()->back()->withInput()->with("msg", "Rujuan tidak ditemukan");

                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                $desa       = Desa::where("nama", "$request->dlop_desa")->first();

                if (empty($status)) {
                    return response()->json([
                        "value"     => 0,
                        "message"   => "Status tidak ada"
                    ]);
                }
                elseif(empty($pekerjaan)){
                    return response()->json([
                        "value"     => 0,
                        "message"   => "Pekerjaan tidak ada"
                    ]);
                }
                elseif(empty($desa)){
                    return response()->json([
                        "value"     => 0,
                        "message"   => "Desa tidak ditemukan didaerah pati"
                    ]);
                }

                if($request->jenis_tanah != 1){
                    return response()->json([
                        "message" => "jenis tanah yang di pilih harus tanah dan bangunan"
                    ]);
                }elseif($request->jenis_tanah == 1){
                    $kondisi                    = Kondisi::where("id", $request->kondisi)->pluck("id")->first();
                    $jenisPenggunaanBangunan    = JenisPenggunaanBangunan::where("id", $request->penggunaan)->pluck("id")->first();
                    $konstruksi                 = Konstruksi::where("id", $request->konstruksi)->pluck("id")->first();
                    $atap                       = Atap::where("id", $request->atap)->pluck("id")->first();
                    $dinding                    = Dinding::where("id", $request->dinding)->pluck("id")->first();
                    $lantai                     = Lantai::where("id", $request->lantai)->pluck("id")->first();
                    $langit                     = Langit::where("id", $request->langit)->pluck("id")->first();
                    
                    $uu = Str::random(40) .time();
                    if(Spop::where("uuid", $uu)->first() != null)
                        $uu = $uu.time();
                            
                    $spop           = new Spop();
                    $spop->uuid     = $uu;
                    $spop->nop      = str_replace(".", "", $rujukan->nop);
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
                        "nomor_hp"          => $request->dsp_no_hp,
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

                    //  return redirect("/pemutakhiran/" . $spop->uuid . "/bangunan/create");
                     return response()->json([
                         "value"    => 1,
                         "data"     => $spop // ke route create bangunan pemutakhiran
                     ]);
                     // redirect to bangunan new

                }else{
                    return response()->json([
                        "value"     => 0,
                        "message"   => "jenis tanah tidak ada"
                    ]);
                }

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

        return response()->json([
            "value"     => 1,
            "message"   => "data untuk pembuatan bangunan berhasil",
            "data"      => [
                "urutan bangunan" => $value,
                $jenisPenggunaanBangunans,
                $kondisis,
                $konstruksis,
                $ataps,
                $dindings,
                $lantais,
                $langits,
                $uui,
            ]
        ]); 
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

                if (empty($spop)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "nop belum ada"
                    ]);
                }

                elseif (empty($kondisi)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "Kondisi tidak ada"
                    ]);
                }

                elseif (empty($jenisPenggunaanBangunan)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "jenisPenggunaanBangunan tidak ada"
                    ]);
                }
                
                elseif (empty($dinding)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "dinding tidak ada"
                    ]);
                }
                
                elseif (empty($lantai)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "lantai tidak ada"
                    ]);
                }
                
                elseif (empty($langit)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "langit tidak ada"
                    ]);
                }
                      
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
                // return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "bangunan berhasil ditambahkan");
                return response()->json([
                    "value"     => 1,
                    "message"   => "bangunan berhasil ditambahkan",
                    "data"      => $spop
                ]);

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

                if (empty($spop)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "nop belum ada"
                    ]);
                }

                elseif (empty($kondisi)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "Kondisi tidak ada"
                    ]);
                }

                elseif (empty($jenisPenggunaanBangunan)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "jenisPenggunaanBangunan tidak ada"
                    ]);
                }
                
                elseif (empty($dinding)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "dinding tidak ada"
                    ]);
                }
                
                elseif (empty($lantai)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "lantai tidak ada"
                    ]);
                }
                
                elseif (empty($langit)){
                    return response()->json([
                        "value" => 0,
                        "err"   => "langit tidak ada"
                    ]);
                }
                        
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
                    "kondisi_id"                    => $kondisi,
                    "konstruksi_id"                 => $konstruksi,
                    "atap_id"                       => $atap,
                    "dinding_id"                    => $dinding,
                    "lantai_id"                     => $lantai,
                    "langit_id"                     => $langit,
                    "spop_id"                       => $spop->id
                ]);
                
                // $value = session('urutan_bangunan');
                // $value++;
                // session(["urutan_bangunan" => $value]);
                // jika nop ngga kosong
                return redirect("/pemutakhiran/" . $spop->uuid . "/bangunan/create");
                return response()->json([
                    "data" => $spop
                ]);
                // redirect to bangunan new
                break;
            default:
                return redirect()->back()->withInput()->with("err","tidak ada action");
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
            "gambars",
            "gambars.kategori",
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
        $kategori                   = Kategori::get();

        return response()->json([
            "value" => 1,
            "message" => "data pemutakhiran per id berhasil di tampilkan",
            "data"  => [
                $spop,
                $statuses,
                $pekerjaans,
                $jenisPenggunaanBangunans,
                $kondisis,
                $konstruksis,
                $ataps,
                $dindings,
                $lantais,
                $langits,
                $jenisTanah,
                $kategori
            ]
        ]);
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
            "gambars",
            "gambars.kategori",
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
        $kategori                   = Kategori::get();

        return response()->json([
            "value" => 1,
            "message" => "berhasil",
            "data"  => [
                $spop,
                $desas,
                $statuses,
                $pekerjaans,
                $jenisPenggunaanBangunans,
                $kondisis,
                $konstruksis,
                $ataps,
                $dindings,
                $lantais,
                $langits,
                $jenisTanah,
                $kategori,
            ]
        ]);
    
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
        if (empty($spop)) abort(404);
            // die("Nop tidak ada");

        $status     = Status::where("id", $request->status)->pluck("id")->first();
        $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
        $desa       = Desa::where("nama", "$request->dlop_desa")->first();

        if (empty($status)) abort(404);
        // die("status tidak ada");
        elseif(empty($pekerjaan)) abort(404);
            // die("pekerjaan tidak ada");
        elseif(empty($desa)) abort(404);
            // return redirect()->back()->withInput()->with("msg", "desa tidak ditemukan");

        if($request->jenis_tanah == 2 || $request->jenis_tanah == 3 || $request->jenis_tanah == 1){

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
                    "nomor_hp"         => $request->dsp_no_hp,
                    "status_id"         => $status,
                    "pekerjaan_id"      => $pekerjaan,
                    "desa"              => $request->dsp_desa,
                    "kabupaten"         => $request->dsp_kabupaten,
                ]);

            if($request->has("gambar")){
                
                /**
                 * untuk pengujian keberadaan kategori
                 */
                foreach($request->gambar as $key => $value){
                    $kategori = Kategori::where("id", $key)->first();
                    
                    if($kategori->id == null){
                        // return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
                        return response()->json([
                            "value"     => 0,
                            "message"   => "Kategori gambar tidak ada"
                        ]);
                    }
                }
                /**
                 * insert @image
                 */
                
                foreach($request->gambar as $image => $valueImage){
                    foreach($valueImage as $key => $gambar){
                        dd($valueImage);
                        $name = Str::random(20).time(). "." .$gambar->getClientOriginalExtension();

                        $gambar->storeAs(
                            'public/data_spop', $name
                        );

                        Gambar::create([
                            "nama"          => $name,
                            "spop_id"       => $spop->id,
                            "kategori_id"   => $image
                        ]);
                    }
                }
            }

            $dataTanah = DataTanah::where("spop_id", $spop->id)->update([
                "luas_tanah"        => $request->dsp_luas_tanah,
                "jenis_tanah_id"    => $request->jenis_tanah,
            ]);

                // jika nop ngga kosong
                // return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "data pemilik telah berhasil diubah");
                return response()->json([
                    "value" => 1,
                    "data"  => $spop,
                    "uuid"  => $spop->uuid,
                    "message"   => "data pemilik telah berhasil diubah"
                ]);
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

        return response()->json([
            "value" => 1,            
            "data"  => [
                $rincianDataBangunan,
                $jenisTanah,
                $statuses,
                $pekerjaans,
                $jenisPenggunaanBangunans,
                $kondisis,
                $konstruksis,
                $ataps,
                $dindings,
                $lantais,
                $langits,
                $spop
            ]
        ]);
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
            "kondisi_id"                    => $kondisi,
            "konstruksi_id"                 => $konstruksi,
            "atap_id"                       => $atap,
            "dinding_id"                    => $dinding,
            "lantai_id"                     => $lantai,
            "langit_id"                     => $langit,
        ]);

        // return redirect("/pemutakhiran/$spop->uuid/bangunan/$rincianDataBangunan->uuid");
        return response()->json([
            "value"     => 1,
            "spop_uuid" => $spop->uuid,
            "bangunan_uuid" => $rincianDataBangunan->uuid,
            "data"  => [
                "spop"      => $spop,
                "rincian"   => $rincianDataBangunan
            ]
        ]);
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

        return response()->json([
            "value" => 1,
            "data"  => [
                $rincianDataBangunan,
                $jenisTanah,
                $statuses,
                $pekerjaans,
                $jenisPenggunaanBangunans,
                $kondisis,
                $konstruksis,
                $ataps,
                $dindings,
                $lantais,
                $langits,
                $spop
            ]
        ]);

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
        $rujukan    = urlencode($request->rujukan);
        
        if( empty(trim($rujukan))){

            return response()->json([
                "value"     => 0,
                "message"   => "nomor nop kosong"
            ]);

        }else{

            $this->validate($request, [
                "rujukan" => "min:14"
            ]);

            $kec        = substr($rujukan, 4,3);
            $des        = substr($rujukan, 7,3);
            $blok       = substr($rujukan, 10,3);
            $no_urut    = substr($rujukan, 13,4);
            $kode       = substr($rujukan, 17,18);

            $nop            = "33.18.$kec.$des.$blok.$no_urut.$kode";
            
            $nop_replace    = str_replace(".", "", $nop);

            $resultRujukan    = Rujukan::where("nop", $nop)->first();

            if(empty($resultRujukan)){
                /**
                 * response rujukan empty
                 */
                return response()->json([
                    "value"     => 0,
                    "message"   => "data rujukan kosong/tidak ada"
                ]);
                
            }
            
            /**
             * response rujukan ada
             */
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

                /**
                 * response jika sudah ada data di spop , langsung lempar ke halaman pemutakhiran
                 */
                return response()->json(
                    [
                        "value"     => 1,
                        "message"   => "data spop ada",
                        "data"      => $spop
                    ], 
                    200
                );

            }elseif(empty($spop) && !empty($resultRujukan)){

                /**
                 * response ke halaman rujukan jika ada data rujukan  
                 */
                return response()->json(
                    [
                        "value"     => 1,
                        "message"   => "data rujukan ada",
                        "data"      => $resultRujukan
                    ], 
                    200
                );
            }else{
                return response()->json(
                    [
                        "value"     => 0,
                        "message"   => "nomor nop tidak ada",
                    ],
                    404
                );
            }
        }
    }

}
