<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $fillable = ["id", "nama"];

    public function dataLetakObjek()
    {
        return $this->hasMany("App\Models\DataLetakObjek");
    }
}
