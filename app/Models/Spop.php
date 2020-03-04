<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spop extends Model
{
    protected $fillable = [
        "uuid",
        "nop",
        "nop_asal",
        "data_letak_objek_id",
        "data_subjek_pajak_id",
        "data_tanah_id",
        "user_id"
    ];

    public function rincianDataBangunans()
    {
        return $this->hasMany("App\Models\RincianDataBangunan");
    }

    public function dataLetakObjek()
    {
        return $this->hasOne("App\Models\DataLetakObjek");
    }

    public function dataSubjekPajak()
    {
        return $this->hasOne("App\Models\DataSubjekPajak");
    }

    public function dataTanah()
    {
        return $this->hasOne("App\Models\DataTanah");
    }

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function gambars()
    {
        return $this->hasMany("App\Models\Gambar");
    }
}
