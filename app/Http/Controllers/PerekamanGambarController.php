<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spop;
use App\Models\Gambar;

class PerekamanGambarController extends Controller
{
    public function destroy($uuid, $id)
    {
        $spop   = Spop::where("uuid", $uuid)->first();
        $gambar = Gambar::where("id", $id)->first();

        if(empty($spop))    return abort(403);
        elseif(empty($gambar))  return abort(403);
        else{
            $gambar->delete();
            return redirect()->back()->with("msg", "gambar berhasil di hapus");
        }
    }
}
