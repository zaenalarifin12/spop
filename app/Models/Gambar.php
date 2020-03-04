<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    protected $fillable = [
        "nama", "spop_id", "kategori_id"
    ];

    public function kategori()
    {
        return $this->belongsTo("App\Models\Kategori");
    }

    public function spop()
    {
        return $this->belongsTo("App\Models\Spop");
    }
}
