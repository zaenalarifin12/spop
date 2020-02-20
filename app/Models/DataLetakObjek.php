<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLetakObjek extends Model
{
    protected $fillable = [
        "nama_jalan",
        "desa_id",
        "blok_kav",
        "rw",
        "rt",
        "spop_id"
    ];

    public function spop()
    {
        return $this->belongsTo("App\Models\Spop");
    }

    public function desa()
    {
        return $this->belongsTo("App\Models\Desa");
    }
}
