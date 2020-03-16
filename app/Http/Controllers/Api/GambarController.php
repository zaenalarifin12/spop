<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Spop;
use App\Models\Gambar;

class GambarController extends Controller
{
    public function destroy($uuid, $id)
    {
        $spop   = Spop::where("uuid", $uuid)->first();
        $gambar = Gambar::where("id", $id)->first();

        if(empty($spop))    return abort(404);
        elseif(empty($gambar))  return abort(404);
        else{
            $gambar->delete();
            return response()->json([
                "value"     => 1,
                "message"   =>"gambar berhasil di hapus"
            ]);
        }
    }
}
