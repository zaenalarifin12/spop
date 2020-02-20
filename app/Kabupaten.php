<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $fillable = ["id", "nama"];

    public function kecamatans()
    {
        return $this->hasMany("App\Models\Kecamatan");
    }
}
