<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSubjekPajak extends Model
{
    protected $fillable = [
        "nama_subjek_pajak",
        "nama_jalan",
        "rt",
        "rw",
        "nomor_ktp",
        "nomor_hp",
        "status_id",
        "pekerjaan_id",
        "desa",
        "kabupaten",
        "spop_id"
    ];

    public function spop()
    {
        return $this->belongsTo("App\Models\Spop");
    }

    public function status()
    {
        return $this->belongsTo("App\Models\Status");
    }

    public function pekerjaan()
    {
        return $this->belongsTo("App\Models\Pekerjaan");
    }
}
