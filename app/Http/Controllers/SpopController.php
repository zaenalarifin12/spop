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
use App\Models\Gambar;
use App\Models\Kategori;

use Auth;
use DataTables;

class SpopController extends Controller
{

    /**
     * SECTION
     * FOR DIFFERENT PEREKAMAN AND PEMUTAKHIRAN
     */

    public function json_spop($kategori_spop)
    {
        if (Auth::user()->role == 1){
            $spops = Spop::with("user")->where("kategori", $kategori_spop)->get();
        }else{
            $spops = Spop::with("user")->where("kategori", $kategori_spop)->where("user_id", Auth::user()->id)->get();
        }


        /**
         * SECTION
         */
        if($kategori_spop == 0){
            return DataTables::of($spops)
            ->addColumn('action', function($row) {
                return '<a href="/pemutakhiran/'. $row->uuid .'" class="btn btn-primary">Lihat</a>';
            })->make(true);
        }elseif($kategori_spop == 1){
            return DataTables::of($spops)
            ->addColumn('action', function($row) {
                return '<a href="/perekaman/'. $row->uuid .'" class="btn btn-primary">Lihat</a>';
            })->make(true);
        }
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
            if(!empty($spop)) return redirect("/pemutakhiran/$spop->uuid");
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

            return view("pemutakhiran.create", compact([
                "rujukan", "my_nop", "wp_desa", "wp_rt", "wp_rw", "wp_kecamatan",
    
                "op_desa", "op_rt", "op_rw",
    
                "jenisTanah", "statuses", "pekerjaans", "jenisPenggunaanBangunans",
                "kondisis", "konstruksis", "ataps", "dindings", "lantais", "langits",
                "desas", "kategori"
            ]));
        }else if($kategori_spop == 1){
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
                    return redirect()->back()->withInput()->with("msg", "Status tidak ada");
                elseif(empty($pekerjaan))
                    return redirect()->back()->withInput()->with("msg", "Pekerjaan tidak ada");
                if(empty($desa))
                    return redirect()->back()->withInput()->with("msg", "Desa tidak ditemukan didaerah pati");

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
                                return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
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
                        return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "data pemutakhiran berhasil di tambahkan");
                    } else if ($kategori_spop == 1){
                        return redirect("/perekaman/" . $spop->uuid)->with("msg", "data perekaman berhasil di tambahkan");
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
                                return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
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
                        return redirect("/pemutakhiran/" . $spop->uuid);
                     } else if($kategori_spop == 1){
                        return redirect("/perekaman/" . $spop->uuid);
                     }
                    // redirect to add new
                }else{
                    return redirect()->back()->withInput()->with("msg", "jenis tanah yang di pilih tidak ada");
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
                        return redirect()->back()->withInput()->with("msg", "Rujuan tidak ditemukan");
                }

                $status     = Status::where("id", $request->status)->pluck("id")->first();
                $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
                $desa       = Desa::where("nama", "$request->dlop_desa")->first();

                if (empty($status))
                    return redirect()->back()->withInput()->with("msg", "Status tidak ada");

                elseif(empty($pekerjaan))
                    return redirect()->back()->withInput()->with("msg", "Pekerjaan tidak ada");

                elseif(empty($desa))
                    return redirect()->back()->withInput()->with("msg", "Desa tidak ditemukan didaerah pati");

                if($request->jenis_tanah != 1){
                    return redirect()->back()->withInput()->with("msg", "jenis tanah yang di pilih harus 1");

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
                        return redirect("/pemutakhiran/" . $spop->uuid . "/bangunan/create");
                     } else if( $kategori_spop == 1) {
                        return redirect("/perekaman/" . $spop->uuid . "/bangunan/create");
                     }

                }else
                    return redirect()->back()->withInput()->with("msg", "jenis tanah tidak ada");        
                break;
            default:
                return redirect()->back()->withInput()->with("msg", "tidak ada action");        
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
                "jenisTanah",
                "kategori"
            ]));
        }else if($kategori_spop == 1){
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
                "jenisTanah",
                "kategori"
            ]));
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

        if(empty($spop)) abort(404);

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
                "jenisTanah",
                "kategori"
            ]));
        } else if ($kategori_spop ==  1) {
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
        if (empty($spop)) abort(404);

        $status     = Status::where("id", $request->status)->pluck("id")->first();
        $pekerjaan  = Pekerjaan::where("id", $request->pekerjaan)->pluck("id")->first();
        $desa       = Desa::where("nama", "$request->dlop_desa")->first();

        if (empty($status)) 
            return redirect()->back()->withInput()->with("msg", "status tidak ditemukan");
        elseif(empty($pekerjaan))
            return redirect()->back()->withInput()->with("msg", "pekerjaan tidak ditemukan");
        elseif(empty($desa))
            return redirect()->back()->withInput()->with("msg", "desa tidak ditemukan");

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
                        return redirect()->back()->withInput()->with("msg", "Kategori gambar tidak ada");
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
                    return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "data pemutakhiran telah berhasil diubah");
                }else if($kategori_spop == 1){
                    return redirect("/perekaman/" . $spop->uuid)->with("msg", "data perekaman telah berhasil diubah");
                }
                // redirect to add new
        }else{
            return redirect()->back()->withInput()->with("msg", "jenis tanah yang di pilih tidak ada");
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

        }elseif($kategori_spop == 1){
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

                // redirect to add new
                session()->forget('urutan_bangunan'); // menghapus session
                if ($kategori_spop == 0) {
                    return redirect("/pemutakhiran/" . $spop->uuid)->with("msg", "bangunan berhasil ditambahkan");
                } else if ($kategori_spop == 1) {
                    return redirect("/perekaman/" . $spop->uuid)->with("msg", "bangunan berhasil ditambahkan");
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
                // jika nop ngga kosong
                if ($kategori_spop == 0) {
                    return redirect("/pemutakhiran/" . $spop->uuid . "/bangunan/create");
                } else if($kategori_spop == 1) {
                    return redirect("/perekaman/" . $spop->uuid . "/bangunan/create");
                }
                
                // redirect to bangunan new
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

        if ($kategori_spop == 0) {
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
        } else if($kategori_spop == 1) {
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

        if(empty($spop) || empty($rincianDataBangunan)) abort(404);

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
            return redirect("/pemutakhiran/$spop->uuid/bangunan/$rincianDataBangunan->uuid")->with("msg", "data bangunan berhasil di edit");
        }else if($kategori_spop == 1){
            return redirect("/perekaman/$spop->uuid/bangunan/$rincianDataBangunan->uuid")->with("msg", "data bangunan berhasil di edit");
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
        } else if($kategori_spop == 1) {
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
            return redirect("/pemutakhiran/".$spop->uuid)->with("msg", "data bangunan berhasil di hapus");
        } else {
            return redirect("/perekaman/".$spop->uuid)->with("msg", "data bangunan berhasil di hapus");
        }
    
    }
}
