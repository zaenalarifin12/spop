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

    public function store(Request $request)
    {
        /**
         * for nop asal
         */

        /**
            $kec    = $request->kec;
            $des    = $request->des;
            $blok   = $request->blok;
            $no_urut= $request->no_urut;
            $kode   = $request->kode;
         */
        
        $tambahan_input = $request->nop_asal;
        $nop            = "33.18.$tambahan_input";
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

                // masih kurang nop
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

                }else abort(404);
                    // die("jenis tanah tidak ada");        
                break;
            default:
                die("tidak ada action");
        }
    }

}
