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
use App\Models\Kategori;
use App\Models\Gambar;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use DataTables;

use Auth;

class PerekamanController extends Controller
{
    
    /**
     * TODO 
     * edit admin dan pegawai beda , bagian input nop
     */

    public function index()
    {
        return view("perekaman.index");
    }

    public function json()
    {
        if(Auth::user()->role == 1){
            $spops = Spop::with("user")->where("kategori", 1)->get();
        }else{
            $spops = Spop::with("user")->where("kategori", 1)->where("user_id",Auth::user()->id)->get();
        }

        return DataTables::of($spops)
        ->addColumn('action', function($row) {
            return '<a href="/perekaman/'. $row->uuid .'" class="btn btn-primary">Lihat</a>';
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
        $desas                      = Desa::get()->pluck("nama");
        $kategori                   = Kategori::get();

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
            "desas",
            "kategori"
        ]));
    }

    public function store(Request $request)
    {
        $kec    = $request->kec;
        $des    = $request->des;
        $blok   = $request->blok;
        $no_urut= $request->no_urut;
        $kode   = $request->kode;

        $nop            = "33.18.$kec.$des.$blok.$no_urut.$kode";
        $nop_replace    = str_replace(".", "", $nop);
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

                $spop_asal  = Spop::where("nop", $nop_replace)->first(); #mencari nop di table
                $rujukan    = Rujukan::where("nop", $nop)->first();

                // if (empty($spop_asal) && empty($rujukan))
                //     return redirect()->back()->withInput()->with("msg", "nop tidak ada");
                // die("nop belum ada");
                    
                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                if (empty($status)) 
                    return redirect()->back()->withInput()->with("msg", "status tidak ada");
                    // die("status tidak ada");
                elseif(empty($pekerjaan))
                    return redirect()->back()->withInput()->with("msg", "pekerjaan tidak ada");
                    // die("pekerjaan tidak ada");
                
                $uu = Str::random(40) .time();
                if(Spop::where("uuid", $uu)->first() != null)
                    $uu = Str::random(40) .time();

                $desa = Desa::where("nama", "$request->dlop_desa")->first();
                if(empty($desa))
                    return redirect()->back()->withInput()->with("msg", "Desa tidak ditemukan didaerah pati");

                if($request->jenis_tanah == 1){

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

                    $kondisi                    = Kondisi::where("id", $request->kondisi)->pluck("id")->first();
                    $jenisPenggunaanBangunan    = JenisPenggunaanBangunan::where("id", $request->penggunaan)->pluck("id")->first();
                    $konstruksi                 = Konstruksi::where("id", $request->konstruksi)->pluck("id")->first();
                    $atap                       = Atap::where("id", $request->atap)->pluck("id")->first();
                    $dinding                    = Dinding::where("id", $request->dinding)->pluck("id")->first();
                    $lantai                     = Lantai::where("id", $request->lantai)->pluck("id")->first();
                    $langit                     = Langit::where("id", $request->langit)->pluck("id")->first();

                    $spop                   = new Spop();
                    $spop->uuid             = $uu;
                    $spop->nop_asal         = $nop_replace;
                    $spop->kategori         = 1;
                    $spop->user_id          = Auth::user()->id;
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
                                return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
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

                    return redirect("/perekaman/" . $spop->uuid)->with("msg", "data perekaman berhasil di tambahkan");

                }elseif($request->jenis_tanah == 2 || $request->jenis_tanah == 3){

                    $spop                   = new Spop();
                    $spop->uuid             = $uu;
                    $spop->nop_asal         = $nop_replace;
                    $spop->kategori         = 1;
                    $spop->user_id          = Auth::user()->id;
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
                                return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
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

                    return redirect("/perekaman/" . $spop->uuid);
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
                    "daya"                  => "required|numeric|min:0",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);

                $spop_asal = Spop::where("nop", $nop_replace)->first(); #mencari rujukan di table
                $rujukan    = Rujukan::where("nop", $nop)->first();

                if (empty($spop_asal) || empty($rujukan))
                    return redirect()->back()->withInput()->with("msg", "nop tidak ada");
                
                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();

                if (empty($status))
                    return redirect()->back()->withInput()->with("msg", "nop tidak ada");
                    // die("status tidak ada");
                elseif(empty($pekerjaan)) 
                    return redirect()->back()->withInput()->with("msg", "pekerjaan tidak ada");
                    // die("pekerjaan tidak ada");
                
                $uu = Str::random(40);
                if(Spop::where("uuid", $uu)->first() != null)
                    $uu = $uu.time();
                
                $desa = Desa::where("nama", "$request->dlop_desa")->first();
                if(empty($desa))
                    return redirect()->back()->withInput()->with("msg", "Desa tidak ditemukan didaerah pati");

                $spop           = new Spop();
                $spop->uuid     = $uu;
                $spop->nop_asal = $nop_replace;
                $spop->kategori = 1;
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

                if($request->jenis_tanah == 2 || $request->jenis_tanah == 3){
                    

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
                     return redirect("/perekaman/" . $spop->uuid);

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
                     return redirect("/perekaman/" . $spop->uuid . "/bangunan/create");
                     // redirect to bangunan new

                }else 
                    return redirect()->back()->withInput()->with("msg","jenis tanah tidak ada");        
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

        return view("perekaman.createBangunan", compact([
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
                    return redirect()->back()->withInput()->with("err","nop belum ada");

                if (empty($kondisi))
                    return redirect()->back()->withInput()->with("err","Kondisi tidak ada");

                if (empty($jenisPenggunaanBangunan))
                    return redirect()->back()->withInput()->with("err","jenisPenggunaanBangunan tidak ada");
                
                if (empty($dinding))
                return redirect()->back()->withInput()->with("err","dinding tidak ada");
                
                if (empty($lantai))
                    return redirect()->back()->withInput()->with("err","lantai tidak ada");
                
                if (empty($langit))
                    return redirect()->back()->withInput()->with("err","langit tidak ada");
                        
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
                return redirect("/perekaman/" . $spop->uuid)->with("msg", "bangunan berhasil ditambahkan");

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

                if (empty($spop))
                return redirect()->back()->withInput()->with("err","nop belum ada");

                if (empty($kondisi))
                    return redirect()->back()->withInput()->with("err","Kondisi tidak ada");

                if (empty($jenisPenggunaanBangunan))
                    return redirect()->back()->withInput()->with("err","jenisPenggunaanBangunan tidak ada");
                
                if (empty($dinding))
                return redirect()->back()->withInput()->with("err","dinding tidak ada");
                
                if (empty($lantai))
                    return redirect()->back()->withInput()->with("err","lantai tidak ada");
                
                if (empty($langit))
                    return redirect()->back()->withInput()->with("err","langit tidak ada");
                        
                        
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
                
                $value = session('urutan_bangunan');
                $value++;
                session(["urutan_bangunan" => $value]);
                
                return redirect("/perekaman/" . $spop->uuid . "/bangunan/create");
                
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
            "dataTanah",
            "rincianDataBangunans",
            "rincianDataBangunans.kondisi",
            "rincianDataBangunans.konstruksi",
            "rincianDataBangunans.atap",
            "rincianDataBangunans.lantai",
            "rincianDataBangunans.langit",
            "gambars",
            "gambars.kategori"
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

        return view("perekaman.show", compact([
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
            "gambars",
            "gambars.kategori"
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

        return view("perekaman.edit", compact([
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
            "jenisTanah",
            "kategori"
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
        if (empty($spop)) abort(404);
            // die("Nop tidak ada");

        $status     = Status::where("id", $request->status)->pluck("id")->first();
        $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
        $desa = Desa::where("nama", "$request->dlop_desa")->first();

        if (empty($status)) abort(404);
            // die("status tidak ada");
        elseif(empty($pekerjaan)) abort(404);
            // die("pekerjaan tidak ada");
        elseif(empty($desa))
            return redirect()->back()->withInput()->with("msg", "desa tidak ditemukan");

        if($request->jenis_tanah == 2 || $request->jenis_tanah == 3 || $request->jenis_tanah == 1){

            if(Auth::user()->role == 1){
                $this->validate($request, [
                    "nop_kec"      => "required",
                    "nop_des"      => "required",
                    "nop_blok"     => "required",
                    "nop_no_urut"  => "required",
                    "nop_kode"     => "required",
                ]);

                $kec    = $request->nop_kec;
                $des    = $request->nop_des;
                $blok   = $request->nop_blok;
                $no_urut= $request->nop_no_urut;
                $kode   = $request->nop_kode;

                $nop_asal_kec       = $request->nop_asal_kec;
                $nop_asal_des       = $request->nop_asal_des;
                $nop_asal_blok      = $request->nop_asal_blok;
                $nop_asal_no_urut   = $request->nop_asal_no_urut;
                $nop_asal_kode      = $request->nop_asal_kode;

                if(empty($nop_asal_kec) || empty($nop_asal_des) || empty($nop_asal_blok) || empty($nop_asal_no_urut) || empty($nop_asal_kode))
                    return redirect()->back()->withInput()->with("msg", "nop asal ada yang kosong");

                $nop            = "33.18.$kec.$des.$blok.$no_urut.$kode";
                $nop_replace    = str_replace(".", "", $nop);

                $nop_asal       = "33.18. $nop_asal_kec . $nop_asal_des . $nop_asal_blok . $nop_asal_no_urut . $nop_asal_kode";
                $nop_asal_replace    = str_replace(".", "", $nop_asal);

                $spop->update([
                    "nop"       => $nop_replace,
                    "nop_asal"  => $nop_asal
                    ]);
            }

            $nop_asal_kec       = $request->nop_asal_kec;
            $nop_asal_des       = $request->nop_asal_des;
            $nop_asal_blok      = $request->nop_asal_blok;
            $nop_asal_no_urut   = $request->nop_asal_no_urut;
            $nop_asal_kode      = $request->nop_asal_kode;

            if(empty($nop_asal_kec) || empty($nop_asal_des) || empty($nop_asal_blok) || empty($nop_asal_no_urut) || empty($nop_asal_kode))
                    return redirect()->back()->withInput()->with("msg", "nop asal ada yang kosong");

            $nop_asal       = "33.18.$nop_asal_kec.$nop_asal_des.$nop_asal_blok.$nop_asal_no_urut.$nop_asal_kode";
            $nop_asal_replace    = str_replace(".", "", $nop_asal);

            $spop->update([
                "nop_asal"  => $nop_asal_replace
                ]);
        

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

                if($request->has("gambar")){
                    /**
                     * untuk pengujian keberadaan kategori
                     */
                    foreach($request->gambar as $key => $value){
                        $kategori = Kategori::where("id", $key)->first();
                        if($kategori->id == null){
                            return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
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

            $dataTanah = DataTanah::where("spop_id", $spop->id)->update([
                "luas_tanah"        => $request->dsp_luas_tanah,
                "jenis_tanah_id"    => $request->jenis_tanah,
            ]);

            return redirect("/perekaman/" . $spop->uuid)->with("msg", "data pemilik telah berhasil diubah");
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

        return view("perekaman.bangunan.edit", compact([
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

        if (empty($spop))
            return redirect()->back()->withInput()->with("err","nop belum ada");

        if (empty($rincianDataBangunan))
            return redirect()->back()->withInput()->with("err","nop belum ada");

        if (empty($kondisi))
            return redirect()->back()->withInput()->with("err","Kondisi tidak ada");

        if (empty($jenisPenggunaanBangunan))
            return redirect()->back()->withInput()->with("err","jenisPenggunaanBangunan tidak ada");
        
        if (empty($dinding))
        return redirect()->back()->withInput()->with("err","dinding tidak ada");
        
        if (empty($lantai))
            return redirect()->back()->withInput()->with("err","lantai tidak ada");
        
        if (empty($langit))
            return redirect()->back()->withInput()->with("err","langit tidak ada");
                

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

        return redirect("/perekaman/$spop->uuid/bangunan/$rincianDataBangunan->uuid")->with("msg","bangunan berhasil di edit");
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

        return view("perekaman.bangunan.show", compact([
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
        
        return redirect("/perekaman/".$spop->uuid)->with("msg", "data bangunan berhasil di hapus");
    }
}
