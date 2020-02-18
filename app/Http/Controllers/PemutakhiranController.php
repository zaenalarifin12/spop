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

class PemutakhiranController extends Controller
{
    public function create($nop)
    {
        $nop_rujukan = Rujukan::where("nop", $nop)->pluck("nop")->first();

        if (empty($nop_rujukan))
            die("nop rujukan tidak ditemukan");

        $spop = Spop::where("nop", str_replace(".", "", $nop))->first();
            if(!empty($spop)){
                return redirect("/pemutakhiran/$spop->nop");
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

        return view("pemutakhiran.create", compact([
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
            "nop_rujukan"
        ]));
    }

    public function store(Request $request, $nop)
    {
        switch ($request->input("action")) {
            case "save":
                /**
                 * VALIDASI FORM
                 */
                $this->validate($request, [
                    "nop"                   => "required",
                    "dlop_nama_jalan"       => "required",
                    "dlop_blok"             => "required",
                    "dlop_kecamatan"        => "required",
                    "dlop_desa"             => "required",
                    "dlop_rw"               => "required",
                    "dlop_rt"               => "required",
                    "status"                => "required",
                    "pekerjaan"             => "required",
                    "dsp_nama_subjek_pajak" => "required",
                    "dsp_nama_jalan"        => "required",
                    "dsp_kecamatan"         => "required",
                    "dsp_desa"              => "required",
                    "dsp_rw"                => "required",
                    "dsp_rt"                => "required",
                    "dsp_no_ktp"            => "required",
                    "dsp_luas_tanah"        => "required",
                    "jenis_tanah"           => "required", //  masih kurang validasi 1,2,3
                ]);

                $rujukan = Rujukan::where("nop", $nop)->pluck("nop")->first(); #mencari rujukan di table
                if (empty($rujukan))
                    die("nop belum ada");

                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                if (empty($status))
                    die("status tidak ada");
                elseif(empty($pekerjaan))
                    die("pekerjaan tidak ada");
                
                $spop       = new Spop();
                $spop->nop  = (str_replace(".", "", $rujukan));
                $spop->save();

                $DataLetakObjekPajak = DataLetakObjek::create([
                    "nama_jalan"        => $request->dlop_nama_jalan,
                    "desa_id"           => $request->dlop_desa,
                    // $request->dlop_kecamatan;
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
                    "desa_id"           => $request->dsp_desa,
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
                     return redirect("/pemutakhiran/" . $spop->nop);
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
                    "nop"                   => "required",
                    "dlop_nama_jalan"       => "required",
                    "dlop_blok"             => "required",
                    "dlop_kecamatan"        => "required",
                    "dlop_desa"             => "required",
                    "dlop_rw"               => "required",
                    "dlop_rt"               => "required",
                    "status"                => "required",
                    "pekerjaan"             => "required",
                    "dsp_nama_subjek_pajak" => "required",
                    "dsp_nama_jalan"        => "required",
                    "dsp_kecamatan"         => "required",
                    "dsp_desa"              => "required",
                    "dsp_rw"                => "required",
                    "dsp_rt"                => "required",
                    "dsp_no_ktp"            => "required",
                    "dsp_luas_tanah"        => "required",
                    "jenis_tanah"           => "required|in:1", //  masih kurang validasi 1,2,3
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required",
                    "jumlah_lantai"         => "required",
                    "tahun_dibangun"        => "required",
                    "tahun_renovasi"        => "required",
                    "jumlah_bangunan"       => "required",
                    "daya"                  => "required",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);

                
                $rujukan = Rujukan::where("nop", $nop)->pluck("nop")->first(); #mencari rujukan di table
                if (empty($rujukan))
                    die("nop belum ada");

                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                if (empty($status))
                    die("status tidak ada");
                elseif(empty($pekerjaan))
                    die("pekerjaan tidak ada");
                
                $spop       = new Spop();
                $spop->nop  = (str_replace(".", "", $rujukan));
                $spop->save();

                $DataLetakObjekPajak = DataLetakObjek::create([
                    "nama_jalan"        => $request->dlop_nama_jalan,
                    "desa_id"           => $request->dlop_desa,
                    // $request->dlop_kecamatan;
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
                    "desa_id"           => $request->dsp_desa,
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

                    $RincianDataBangunan = RincianDataBangunan::create([
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

                     // session for urutan bangunan
                     session(["urutan_bangunan" => 2]);

                     // jika nop ngga kosong
                     return redirect("/pemutakhiran/" . $spop->nop . "/bangunan/create");
                     // redirect to bangunan new

                }else
                    die("jenis tanah tidak ada");        

                break;
            default:
                die("tidak ada action");
        }
    }

    public function createBangunan($nop)
    {
        $nop;
        $value = session('urutan_bangunan');

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
            "nop"
        ]))->with("urutan_bangunan", $value);
    }

    public function storeBangunan(Request $request, $nop)
    {

        switch ($request->input("action")) {
            case "save":

                /**
                 * VALIDASI FORM
                 */
                $this->validate($request, [
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required",
                    "jumlah_lantai"         => "required",
                    "tahun_dibangun"        => "required",
                    "tahun_renovasi"        => "required",
                    "jumlah_bangunan"       => "required",
                    "daya"                  => "required",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);
                
                $spop  = Spop::where("nop", $nop)->first();


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
                        
                $RincianDataBangunan = RincianDataBangunan::create([
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
                return redirect("/pemutakhiran/" . $spop->nop)->with("msg", "data berhasil ditambahkan");

                break;
            case "tambah":     
                /**
                 * VALIDASI FORM
                 */

                $this->validate($request, [
                    // BANGUNAN
                    "penggunaan"            => "required",
                    "luas_bangunan"         => "required",
                    "jumlah_lantai"         => "required",
                    "tahun_dibangun"        => "required",
                    "tahun_renovasi"        => "required",
                    "jumlah_bangunan"       => "required",
                    "daya"                  => "required",
                    "kondisi"               => "required",
                    "konstruksi"            => "required",
                    "atap"                  => "required",
                    "dinding"               => "required",
                    "lantai"                => "required",
                    "langit"                => "required",
                ]);
                
                $spop  = Spop::where("nop", $nop)->first();

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
                        

                $RincianDataBangunan = RincianDataBangunan::create([
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
                
                $nop;
                $value = session('urutan_bangunan');
                $value++;
                session(["urutan_bangunan" => $value]);
                // jika nop ngga kosong
                return redirect("/pemutakhiran/" . $spop->nop . "/bangunan/create");
                // redirect to bangunan new
                break;
            default:
                die("tidak ada action");
        }
    }

    public function show($nop)
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
            "rincianDataBangunans.langit",
            ])->where("nop", $nop)->first();

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
            "nop",
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

    public function editBangunan($nop, $id)
    {
        $spop = Spop::where("nop", $nop)->first();
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
            ["id"       ,$id],
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

    public function updateBangunan(Request $request, $nop, $id)
    {
        $this->validate($request, [
            // BANGUNAN
            "penggunaan"            => "required",
            "luas_bangunan"         => "required",
            "jumlah_lantai"         => "required",
            "tahun_dibangun"        => "required",
            "tahun_renovasi"        => "required",
            // "jumlah_bangunan"       => "required",
            "daya"                  => "required",
            "kondisi"               => "required",
            "konstruksi"            => "required",
            "atap"                  => "required",
            "dinding"               => "required",
            "lantai"                => "required",
            "langit"                => "required",
        ]);

        $idSpop  = Spop::where("nop", $nop)->pluck("id")->first();
        $rincianDataBangunan    = RincianDataBangunan::where([
            ["id"       ,$id],
            ["spop_id"  ,$idSpop]
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
            // "jumlah_bangunan"               => $request->jumlah_bangunan,
            "kondisi_id"                    => $kondisi,
            "konstruksi_id"                 => $konstruksi,
            "atap_id"                       => $atap,
            "dinding_id"                    => $dinding,
            "lantai_id"                     => $lantai,
            "langit_id"                     => $langit,
        ]);

        return redirect("/pemutakhiran/$nop/bangunan/$rincianDataBangunan->id");
    }

    public function showBangunan($nop, $id)
    {
        $spop = Spop::where("nop", $nop)->first();
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
            ["id"       ,$id],
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

    public function cari(Request $request)
    {
        $cari = urlencode($request->search);
        if(empty($cari)){
            return view("pemutakhiran.cari");
        }else{
            $rujukan = Rujukan::where("nop", $cari)->first();
            
            if (!empty($rujukan)) {
                return view("pemutakhiran.cari", compact($rujukan));
            } else {
                return "rujukan not found";
            }
        }
    }
}
