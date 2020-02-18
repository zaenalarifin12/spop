<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTanah extends Model
{
   protected $fillable = [
       "luas_tanah", "jenis_tanah_id", "spop_id"
    ];

   public function jenisTanah()
   {
       return $this->belongsTo("App\Models\JenisTanah");
   }

   public function Spop()
   {
       return $this->belongsTo("App\Models\Spop");
   }
}
