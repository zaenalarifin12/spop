<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rujukan extends Model
{
    protected $fillable = ["tahun, nop,
                    nama_subjek_pajak,
                    alamat_wp,
                    alamat_op,
                    luas_bumi_sppt,
                    luas_bng_sppt,
                    njop_bumi_sppt,
                    njop_bng_sppt"
                ];
}