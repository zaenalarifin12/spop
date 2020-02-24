<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RincianDataBangunan extends Model
{
    protected $fillable = [
        "uuid",
        "luas_bangunan",
        "jumlah_lantai",
        "tahun_dibangun",
        "tahun_renovasi",
        "daya_listrik",
        "jumlah_bangunan",
        "jenis_penggunaan_bangunan_id",
        "kondisi_id",
        "konstruksi_id",
        "atap_id",
        "dinding_id",
        "lantai_id",
        "langit_id",
        "spop_id",
    ];

    public function spop()
    {
        return $this->belongsTo("App\Models\Spop");
    }

    public function jenisPenggunaanBangunan()
    {
        return $this->belongsTo("App\Models\JenisPenggunaanBangunan");
    }

    public function kondisi()
    {
        return $this->belongsTo("App\Models\Kondisi");
    }

    public function konstruksi()
    {
        return $this->belongsTo("App\Models\Konstruksi");
    }

    public function atap()
    {
        return $this->belongsTo("App\Models\Atap");
    }

    public function dinding()
    {
        return $this->belongsTo("App\Models\Dinding");
    }

    public function lantai()
    {
        return $this->belongsTo("App\Models\Lantai");
    }

    public function langit()
    {
        return $this->belongsTo("App\Models\Langit");
    }

}
