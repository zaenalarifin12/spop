<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spop extends Model
{
    protected $fillable = [
        "nop",
        "nop_asal",
        "data_letak_objek_id",
        "data_subjek_pajak_id",
        "data_tanah_id"
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
}
