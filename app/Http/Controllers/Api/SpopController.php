<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;


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

class SpopController extends Controller
{

    /**
     * SECTION
     * FOR DIFFERENT PEREKAMAN AND PEMUTAKHIRAN
     * @param 0 pemutakhiran
     * @param 1 perekaman
     * 
     * @param dlop  => data letak objek pajak
     * @param dsp   => data subjek pajak
    */

    public function index_spop($kategori_spop)
    {
        /**
         * SECTION
         */
        $spops = Spop::with("user")->where("kategori", $kategori_spop)->where("user_id", Auth::user()->id)->get(); 

        return response()->json([
            "value" => 1,
            "data"  => $spops
        ]);
    }

    public function create_spop($uuid = null, $kategori_spop)
    {
        /**
         * SECTION
         * pemutakhiran
         */
        if ($kategori_spop == 0) {
            $rujukan = Rujukan::where("uuid", $uuid)->first();
            if (empty($rujukan)) abort(404);

            $spop = Spop::where("nop", str_replace(".", "", $rujukan->nop))->first();
            /**
             * jika ada return to @param pemutkhiran/UUID
             */
            if(!empty($spop)){
                return response()->json([
                    "value" => 1,
                    "data"  => $spop
                ]);
            } 
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
        $kategori                   = Kategori::get();

        if($kategori_spop == 0){
            $my_nop      = explode(".", $rujukan->nop);
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
                "data" => [
                    "rujukan"       => $rujukan, 
                    "my_nop"        =>$my_nop, 
                    "wp_desa"       =>$wp_desa, 
                    "wp_rt"         =>$wp_rt, 
                    "wp_rw"         =>$wp_rw, 
                    "wp_kecamatan"  =>$wp_kecamatan,
                    "op_desa"       =>$op_desa, 
                    "op_rt"         =>$op_rt, 
                    "op_rw"         =>$op_rw,
                    "jenisTanah"    =>$jenisTanah, 
                    "statuses"      =>$statuses, 
                    "pekerjaans"    =>$pekerjaans, 
                    "jenisPenggunaanBangunans"  =>$jenisPenggunaanBangunans,
                    "kondisis"      =>$kondisis, 
                    "konstruksis"   =>$konstruksis, 
                    "ataps"         =>$ataps, 
                    "dindings"      =>$dindings, 
                    "lantais"       =>$lantais, 
                    "langits"       =>$langits,
                    "desas"         =>$desas, 
                    "kategori"      =>$kategori
                ]
            ]);
        }else if($kategori_spop == 1){
            return response()->json([
                "value" => 1,
                "data"  => [
                    "jenisTanah"        => $jenisTanah,
                    "statuses"          => $statuses,
                    "pekerjaans"        => $pekerjaans,
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"          => $kondisis,
                    "konstruksis"       => $konstruksis,
                    "ataps"             => $ataps,
                    "dindings"          => $dindings,
                    "lantais"           => $lantais,
                    "langits"           => $langits,
                    "desas"             => $desas,
                    "kategori"          => $kategori,
                ]    
            ]);
        }
        


    }

    public function store_spop($request, $kategori_spop, $uuid = null)
    {
        /**
         * SECTION
         * FOR PEREKAMAN
         */
        if ($kategori_spop == 1) {
            $kec    = $request->kec;
            $des    = $request->des;
            $blok   = $request->blok;
            $no_urut= $request->no_urut;
            $kode   = $request->kode;

            $nop            = "33.18.$kec.$des.$blok.$no_urut.$kode";
            $nop_replace    = str_replace(".", "", $nop);
        }
        

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
                    
                /**
                 * SECTION
                 */
                if($kategori_spop == 0){
                    $rujukan = Rujukan::where("uuid", $uuid)->pluck("nop")->first(); #mencari rujukan di table
                    if (empty($rujukan)) abort(404);

                }else if($kategori_spop == 1){
                    $spop_asal  = Spop::where("nop", $nop_replace)->first(); #mencari nop di table
                    $rujukan    = Rujukan::where("nop", $nop)->first();
                }
                    
                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                $desa       = Desa::where("nama", "$request->dlop_desa")->first();
                
                if (empty($status)) 
                    return response()->json(["value" => 0, "message" => "Status tidak ada"], 404);
                elseif(empty($pekerjaan))
                    return response()->json(["value" => 0, "message" => "Pekerjaan tidak ada"], 404);
                if(empty($desa))
                    return response()->json(["value" => 0, "message" => "Desa tidak ditemukan didaerah pati"], 404);

                $uu = Str::random(40) .time();
                if(Spop::where("uuid", $uu)->first() != null)
                    $uu = Str::random(40) .time();
                
                /**
                 * SECTION 
                 * JENIS TANAH
                 */
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

                    /**
                     * SECTION 
                     */
                    if ($kategori_spop == 0){
                        $spop->nop      = (str_replace(".", "", $rujukan));
                        $spop->kategori = 0;
                    }
                    else if($kategori_spop == 1){
                        $spop->nop_asal = $nop_replace;
                        $spop->kategori = 1;
                    }
                    
                    $spop->user_id          = Auth::user()->id;
                    $spop->save();

                    $spop->dataLetakObjek()->create([
                        "nama_jalan"        => $request->dlop_nama_jalan,
                        "desa_id"           => $desa->id,
                        "blok_kav"          => $request->dlop_blok,
                        "rw"                => $request->dlop_rw,
                        "rt"                => $request->dlop_rt,
                    ]);
                    
                    $spop->dataSubjekPajak()->create([
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
                    ]);

                    if($request->has("gambar")){
                        /**
                         * untuk pengujian keberadaan kategori
                         */
                        foreach($request->gambar as $key => $value){
                            $kategori = Kategori::where("id", $key)->first();
                            if($kategori->id == null){
                                return response()->json(["value" => 0, "message" => "Kategori gambar tidak ada"]);
                            }
                        }
                        /**
                         * insert @image
                         */
                        foreach($request->gambar as $image => $valueImage){
                            foreach($valueImage as $key => $gambar){

                                $name = Str::random(20).time(). ".jpg";

                                $gambar->storeAs(
                                    'public/data_spop', $name
                                );

                                $spop->gambars()->create([
                                    "nama"          => $name,
                                    "kategori_id"   => $image
                                ]);
                            }
                        }
                    }

                    $spop->dataTanah()->create([
                        "luas_tanah"        => $request->dsp_luas_tanah,
                        "jenis_tanah_id"    => $request->jenis_tanah,
                    ]);

                    $random = Str::random(40);
                    if(RincianDataBangunan::where("uuid", $random)->first() != null){
                        $random = Str::random(40) .time();
                    }    

                    $spop->rincianDataBangunans()->create([
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
                    ]);

                    /**
                     * SECTION
                     */
                    if ($kategori_spop == 0) {
                        return response()->json([
                            "value"     => 1, 
                            "uuid"      => $spop->uuid,
                            "data"      => $spop,
                            "message"   => "data pemutakhiran berhasil di tambahkan"]);
                    } else if ($kategori_spop == 1){
                        return response()->json([
                            "value"     => 1, 
                            "uuid"      => $spop->uuid,
                            "data"      => $spop,
                            "message"   => "data perekaman berhasil di tambahkan"]);
                    }
                    
                }elseif($request->jenis_tanah == 2 || $request->jenis_tanah == 3){

                    $spop           = new Spop();
                    $spop->uuid     = $uu;

                    /**
                     * SECTION
                     */
                    if ($kategori_spop == 0) {
                        $spop->nop      = (str_replace(".", "", $rujukan));
                        $spop->kategori = 0;
                    } else if($kategori_spop == 1) {
                        $spop->nop_asal = $nop_replace;
                        $spop->kategori = 1;
                    }

                    $spop->user_id  = Auth::user()->id;
                    $spop->save();

                    $spop->dataLetakObjek()->create([
                        "nama_jalan"        => $request->dlop_nama_jalan,
                        "desa_id"           => $desa->id,
                        "blok_kav"          => $request->dlop_blok,
                        "rw"                => $request->dlop_rw,
                        "rt"                => $request->dlop_rt,
                    ]);
                    
                    $spop->dataSubjekPajak()->create([
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
                    ]);
                    
                    if($request->has("gambar")){
                        /**
                         * untuk pengujian keberadaan kategori
                         */
                        foreach($request->gambar as $key => $value){
                            $kategori = Kategori::where("id", $key)->first();
                            if($kategori->id == null){
                                return response()->json([
                                    "value" => 0,
                                    "message" => "Kategori gambar tidak ada"
                                ], 404);
                            }
                        }
                        /**
                         * insert @image
                         */
                        foreach($request->gambar as $image => $valueImage){
                            foreach($valueImage as $key => $gambar){

                                $name = Str::random(20).time(). ".jpg";

                                $gambar->storeAs(
                                    'public/data_spop', $name
                                );

                                $spop->gambars()->create([
                                    "nama"          => $name,
                                    "kategori_id"   => $image
                                ]);
                            }
                        }
                    }

                    $spop->dataTanah()->create([
                        "luas_tanah"        => $request->dsp_luas_tanah,
                        "jenis_tanah_id"    => $request->jenis_tanah,
                    ]);

                     // jika nop ngga kosong
                     if ($kategori_spop == 0) {
                        return response()->json([
                            "value"     => 1,
                            "uuid"      => $spop->uuid,
                            "data"      => $spop,
                            "message"   => "data pemutakhiran berhasil di simpan"
                        ]);
                     } else if($kategori_spop == 1){
                        return response()->json([
                            "value"     => 1,
                            "uuid"      => $spop->uuid,
                            "data"      => $spop,
                            "message"   => "data perekaman berhasil di simpan"
                        ]);
                     }
                    // redirect to add new
                }else{
                    die("jenis tanah yang di pilih tidak ada");
                }
                break;
            case "tambah":    
                
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

                if($kategori_spop == 0){
                    $rujukan = Rujukan::where("uuid", $uuid)->first(); #mencari rujukan di table
                
                    if (empty($rujukan))
                        return response()->json(["value" => 0, "message" => "Rujuan tidak ditemukan"]);
                }

                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                $desa       = Desa::where("nama", "$request->dlop_desa")->first();

                if (empty($status))
                    return response()->json(["value" => 0, "message" => "Status tidak ada"], 404);

                elseif(empty($pekerjaan))
                    return response()->json(["value" => 0, "message" => "Pekerjaan tidak ada"], 404);

                elseif(empty($desa))
                    return response()->json(["value" => 0, "message" => "Desa tidak ditemukan didaerah pati"], 404);

                if($request->jenis_tanah != 1){
                    return response()->json(["value" => 0, "message" => "jenis tanah yang di pilih harus 1"], 404);

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
                    
                    if ($kategori_spop == 0) {
                        $spop           = new Spop();
                        $spop->uuid     = $uu;
                        $spop->nop      = str_replace(".", "", $rujukan->nop);
                        $spop->kategori = 0;
                        $spop->user_id  = Auth::user()->id;
                        $spop->save();

                    }else if($kategori_spop == 1){
                        $spop           = new Spop();
                        $spop->uuid     = $uu;
                        // $spop->nop_asal      = str_replace(".", "", $rujukan->nop);
                        $spop->kategori = 1;
                        $spop->user_id  = Auth::user()->id;
                        $spop->save();
                    }
            
                    
                    $spop->dataLetakObjek()->create([
                        "nama_jalan"        => $request->dlop_nama_jalan,
                        "desa_id"           => $desa->id,
                        "blok_kav"          => $request->dlop_blok,
                        "rw"                => $request->dlop_rw,
                        "rt"                => $request->dlop_rt,
                    ]);
                    
                    $spop->dataSubjekPajak()->create([
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
                    ]);

                    $spop->dataTanah()->create([
                        "luas_tanah"        => $request->dsp_luas_tanah,
                        "jenis_tanah_id"    => $request->jenis_tanah,
                    ]);

                    $random = Str::random(40);
                    if(RincianDataBangunan::where("uuid", $random)->first() != null){
                        $random = Str::random(40) .time();
                    }    

                    $spop->rincianDataBangunans()->create([
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
                    ]);

                     /**
                      * @return to url create/bangunan
                      */

                     if ($kategori_spop == 0) {
                        return response()->json([
                            "value" => 0, 
                            "uuid"  => $spop->uuid,
                            "data"  => $spop,
                            "message" => "data pemutakhiran $spop->nop berhasil di simpan"
                        ]);
                     } else if( $kategori_spop == 1) {
                        return response()->json([
                            "value" => 0, 
                            "uuid"  => $spop->uuid,
                            "data"  => $spop,
                            "message" => "data pemutakhiran $spop->nop berhasil di simpan"
                        ]);
                     }

                }else
                    return response()->json(["value" => 0, "message" => "jenis tanah tidak ada"], 404);
                break;
            default:
                return response()->json(["value" => 0, "message" => "tidak ada action"], 404);
        }
    }

    public function show_spop($uuid, $kategori_spop)
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

        if($kategori_spop == 0){
            return response()->json([
                "value"     => 1,
                "message"   => "data pemutakhiran berhasil di tampilkan",
                "data"      => [
                    "spop"        => $spop,
                    "statuses"    => $statuses,
                    "pekerjaans"  => $pekerjaans,
                    "jenisPenggunaanBangunans" => $jenisPenggunaanBangunans,
                    "kondisis"    => $kondisis,
                    "konstruksis" => $konstruksis,
                    "ataps"       => $ataps,
                    "dindings"    => $dindings,
                    "lantais"     => $lantais,
                    "langits"     => $langits,
                    "jenisTanah"  => $jenisTanah,
                    "kategori"    => $kategori    
                ]
            ]);        
        }else if($kategori_spop == 1){
            return response()->json([
                "value" => 1,
                "message"   => "data pemutakhiran berhasil di tampilkan",
                "data"  => [
                    "spop" => $spop,
                    "statuses" => $statuses,
                    "pekerjaans" => $pekerjaans,
                    "jenisPenggunaanBangunans" => $jenisPenggunaanBangunans,
                    "kondisis" => $kondisis,
                    "konstruksis" => $konstruksis,
                    "ataps" => $ataps,
                    "dindings" => $dindings,
                    "lantais" => $lantais,
                    "langits" => $langits,
                    "jenisTanah" => $jenisTanah,
                    "kategori" => $kategori
                ]
            ]);
        }
    }

    public function edit_spop($uuid, $kategori_spop)
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

        if(empty($spop)) return response()->json(["value" => 0, "message" => "data spop tidak di temukan"], 404);

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

        if ($kategori_spop == 0) {
            return response()->json([
                "value" => 1,
                "data"  => [
                    "spop"          => $spop,
                    "desas"         => $desas,
                    "statuses"      => $statuses,
                    "pekerjaans"    => $pekerjaans,
                    "jenisPenggunaanBangunans" => $jenisPenggunaanBangunans,
                    "kondisis"      => $kondisis,
                    "konstruksis"   => $konstruksis,
                    "ataps"         => $ataps,
                    "dindings"      => $dindings,
                    "lantais"       => $lantais,
                    "langits"       => $langits,
                    "jenisTanah"    => $jenisTanah,
                    "kategori"      => $kategori
                ]
            ]);
        } else if ($kategori_spop ==  1) {
            return response()->json([
                "value" => 1,
                "data"  => [
                    "spop"          => $spop,
                    "desas"         => $desas,
                    "statuses"      => $statuses,
                    "pekerjaans"    => $pekerjaans,
                    "jenisPenggunaanBangunans" => $jenisPenggunaanBangunans,
                    "kondisis"      => $kondisis,
                    "konstruksis"   => $konstruksis,
                    "ataps"         => $ataps,
                    "dindings"      => $dindings,
                    "lantais"       => $lantais,
                    "langits"       => $langits,
                    "jenisTanah"    => $jenisTanah,
                    "kategori"      => $kategori
                ]
            ]);
        }
        
    }

    public function update_spop($request, $uuid, $kategori_spop)
    {
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

        if (empty($spop)) return response()->json(["value" => 0, "message" => "data spop tidak ada"], 404);

        $status     = Status::where("id", $request->status)->pluck("id")->first();
        $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
        $desa       = Desa::where("nama", "$request->dlop_desa")->first();

        if (empty($status)) 
            return response()->json(["value" => 0, "message" => "status tidak ditemukan" ], 404);
        elseif(empty($pekerjaan))
            return response()->json(["value" => 0, "message" => "pekerjaan tidak ditemukan" ], 404);
        elseif(empty($desa))
            return response()->json(["value" => 0, "message" => "desa tidak ditemukan" ], 404);

        if($request->jenis_tanah == 2 || $request->jenis_tanah == 3 || $request->jenis_tanah == 1){

            /**
             * SECTION
             * PEREKAMAN
             */

             if ($kategori_spop == 1) {
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
                        return response()->json(["value" => 0, "message" => "nop asal ada yang kosong" ], 404);
    
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
                    return response()->json(["value" => 0, "message" => "nop asal ada yang kosong" ], 404);
    
                $nop_asal       = "33.18.$nop_asal_kec.$nop_asal_des.$nop_asal_blok.$nop_asal_no_urut.$nop_asal_kode";
                $nop_asal_replace    = str_replace(".", "", $nop_asal);
    
                $spop->update([
                    "nop_asal"  => $nop_asal_replace
                    ]);
             } 

            DataLetakObjek::where("spop_id", $spop->id)->update([
                "nama_jalan"        => $request->dlop_nama_jalan,
                "desa_id"           => $desa->id,
                "blok_kav"          => $request->dlop_blok,
                "rw"                => $request->dlop_rw,
                "rt"                => $request->dlop_rt,
            ]);
            
            DataSubjekPajak::where("spop_id", $spop->id)
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
                        return response()->json(["value" => 0, "message" => "Kategori gambar tidak ada"], 404);
                    }
                }
                /**
                 * insert @image
                 */
                
                foreach($request->gambar as $image => $valueImage){
                    foreach($valueImage as $key => $gambar){
                        dd($valueImage);
                        $name = Str::random(20).time(). ".jpg";

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

            DataTanah::where("spop_id", $spop->id)->update([
                "luas_tanah"        => $request->dsp_luas_tanah,
                "jenis_tanah_id"    => $request->jenis_tanah,
            ]);

                // jika nop ngga kosong
                if($kategori_spop == 0){
                    return response()->json([
                        "value" => 1, 
                        "uuid"  => $spop->uuid,
                        "data"  => $spop,
                        "message" => "data pemutakhiran telah berhasil diubah"
                    ]);
                }else if($kategori_spop == 1){
                    return response()->json([
                        "value" => 1, 
                        "uuid"  => $spop->uuid,
                        "data"  => $spop,
                        "message" => "data perekaman telah berhasil diubah"
                    ]);
                }
                // redirect to add new
        }else{
            die("jenis tanah yang di pilih tidak ada");
        }
    }


    public function create_bangunan_spop($uuid, $kategori_spop)
    {
        $spop = Spop::with("rincianDataBangunans")->where("uuid", $uuid)->first();
        
        if(empty($spop)) abort(404);
        
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

        if($kategori_spop == 0){
            
            return response()->json([
                "value" => 1,
                "urutan_bangunan" => $value,
                "data"  => [
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"                  => $kondisis,
                    "konstruksis"               => $konstruksis,
                    "ataps"                     => $ataps,
                    "dindings"                  => $dindings,
                    "lantais"                   => $lantais,
                    "langits"                   => $langits,
                    "uuid"                      => $uuid
                ]
            ]);

        }elseif($kategori_spop == 1){
            
            return response()->json([
                "value" => 1,
                "urutan_bangunan" => $value,
                "data"  => [
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"                  => $kondisis,
                    "konstruksis"               => $konstruksis,
                    "ataps"                     => $ataps,
                    "dindings"                  => $dindings,
                    "lantais"                   => $lantais,
                    "langits"                   => $langits,
                    "uuid"                      => $uuid
                ]
            ]);

        }

    }

    public function store_bangunan_spop($request, $uuid, $kategori_spop)
    {
        switch ($request->input("action")) {
            case "save":

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
                    return response()->json(["value" => 0, "message" => "nop belum ada"], 404);

                if (empty($kondisi))
                    return response()->json(["value" => 0, "message" => "Kondisi tidak ada"], 404);

                if (empty($jenisPenggunaanBangunan))
                    return response()->json(["value" => 0, "message" => "jenisPenggunaanBangunan tidak ada"], 404);
                
                if (empty($dinding)) 
                    return response()->json(["value" => 0, "message" => "dinding tidak ada"], 404);
                
                if (empty($lantai))
                    return response()->json(["value" => 0, "message" => "lantai tidak ada"], 404);
                
                if (empty($langit))
                    return response()->json(["value" => 0, "message" => "langit tidak ada"], 404);
                      
                $random = Str::random(40);
                if(RincianDataBangunan::where("uuid", $random)->first() != null){
                    $random = Str::random(40) .time();
                }

                $spop->rincianDataBangunans()->create([
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

                if ($kategori_spop == 0) {
                    return response()->json([
                        "value"     => 1,
                        "message"   => "bangunan berhasil ditambahkan",
                        "uuid"      => $spop->uuid,
                        "data"      => $spop
                    ]);
                } else if ($kategori_spop == 1) {
                    return response()->json([
                        "value"     => 1,
                        "message"   => "bangunan berhasil ditambahkan",
                        "uuid"      => $spop->uuid,
                        "data"      => $spop
                    ]);
                }
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
                    return response()->json(["value" => 0, "message" => "nop belum ada"], 404);

                if (empty($kondisi))
                    return response()->json(["value" => 0, "message" => "Kondisi tidak ada"], 404);

                if (empty($jenisPenggunaanBangunan))
                    return response()->json(["value" => 0, "message" => "jenisPenggunaanBangunan tidak ada"], 404);
                
                if (empty($dinding)) 
                    return response()->json(["value" => 0, "message" => "dinding tidak ada"], 404);
                
                if (empty($lantai))
                    return response()->json(["value" => 0, "message" => "lantai tidak ada"], 404);
                
                if (empty($langit))
                    return response()->json(["value" => 0, "message" => "langit tidak ada"], 404);
                        
                        
                $random = Str::random(40);
                if(RincianDataBangunan::where("uuid", $random)->first() != null){
                    $random = Str::random(40) .time();
                }

                $spop->rincianDataBangunans()->create([
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
                ]);
                
                $value = session('urutan_bangunan');
                $value++;
                session(["urutan_bangunan" => $value]);

                if ($kategori_spop == 0) {
                    return response()->json([
                        "value"     => 1,
                        "message"   => "bangunan berhasil ditambahkan",
                        "uuid"      => $spop->uuid,
                        "data"      => $spop
                    ]);
                } else if ($kategori_spop == 1) {
                    return response()->json([
                        "value"     => 1,
                        "message"   => "bangunan berhasil ditambahkan",
                        "uuid"      => $spop->uuid,
                        "data"      => $spop
                    ]);
                }
                break;
            default:
                return redirect()->back()->withInput()->with("err","tidak ada action");
        }
    }

    public function edit_bangunan_spop($uuid, $uuid_bangunan, $kategori_spop)
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

        if($kategori_spop == 0){
            
            return response()->json([
                "value" => 1,
                "data"  => [
                    "bangunan"                  => $rincianDataBangunan,
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"                  => $kondisis,
                    "konstruksis"               => $konstruksis,
                    "ataps"                     => $ataps,
                    "dindings"                  => $dindings,
                    "lantais"                   => $lantais,
                    "langits"                   => $langits,
                ]
            ]);

        }elseif($kategori_spop == 1){
            
            return response()->json([
                "value" => 1,
                "data"  => [
                    "bangunan"                  => $rincianDataBangunan,
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"                  => $kondisis,
                    "konstruksis"               => $konstruksis,
                    "ataps"                     => $ataps,
                    "dindings"                  => $dindings,
                    "lantais"                   => $lantais,
                    "langits"                   => $langits,
                    
                ]
            ]);

        }
        
    }

    public function update_bangunan_spop($request, $uuid, $uuid_bangunan, $kategori_spop)
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

        if(empty($spop) || empty($rincianDataBangunan)) return response()->json(["value" => 0, "message" => "rincian data bangunan atau spop tidak ditemukan"], 404);

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

        if($kategori_spop == 0){
            return response()->json([
                "value"         => 0,
                "message"       => "data bangunan berhasil di edit",
                "uuid"          => $spop->uuid,
                "uuid_bangunan" => $rincianDataBangunan->uuid
            ]);
        }else if($kategori_spop == 1){
            return response()->json([
                "value"         => 0,
                "message"       => "data bangunan berhasil di edit",
                "uuid"          => $spop->uuid,
                "uuid_bangunan" => $rincianDataBangunan->uuid
            ]);
        }
    }

    public function show_bangunan_spop($uuid, $uuid_bangunan, $kategori_spop)
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

        if(empty($spop) || empty($rincianDataBangunan)) abort(404);

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

        if ($kategori_spop == 0) {
            return response()->json([
                "value" => 1,
                "data"  => [
                    "rincianDataBangunan"   => $rincianDataBangunan,
                    "jenisTanah"            => $jenisTanah,
                    "statuses"              => $statuses,
                    "pekerjaans"            => $pekerjaans,
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"              => $kondisis,
                    "konstruksis"           => $konstruksis,
                    "ataps"                 => $ataps,
                    "dindings"              => $dindings,
                    "lantais"               => $lantais,
                    "langits"               => $langits,
                    "spop"                  => $spop
                ]
            ]);
        } else if($kategori_spop == 1) {
            return response()->json([
                "value" => 1,
                "data"  => [
                    "rincianDataBangunan"   => $rincianDataBangunan,
                    "jenisTanah"            => $jenisTanah,
                    "statuses"              => $statuses,
                    "pekerjaans"            => $pekerjaans,
                    "jenisPenggunaanBangunans"  => $jenisPenggunaanBangunans,
                    "kondisis"              => $kondisis,
                    "konstruksis"           => $konstruksis,
                    "ataps"                 => $ataps,
                    "dindings"              => $dindings,
                    "lantais"               => $lantais,
                    "langits"               => $langits,
                    "spop"                  => $spop
                ]
            ]);
        }
        
    }

    public function destroy_bangunan_spop($uuid, $uuid_bangunan, $kategori_spop)
    {
        $spop = Spop::where("uuid", $uuid)->first();

        $rincianDataBangunan = RincianDataBangunan::where([
            ["uuid", $uuid_bangunan],
            ["spop_id", $spop->id]
        ])->first();

        if(empty($rincianDataBangunan)) abort(404);

        $rincianDataBangunan->delete();

        if ($kategori_spop == 0) {
            return response()->json([
                "value"     => 1,
                "message"   => "data bangunan berhasil di hapus",
                "uuid"      => $spop->uuid,
                "data"      => $spop
            ]);
        } else {
            return response()->json([
                "value"     => 1,
                "message"   => "data bangunan berhasil di hapus",
                "uuid"      => $spop->uuid,
                "data"      => $spop
            ]);
        }
    
    }
}
