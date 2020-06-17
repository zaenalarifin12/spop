<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spop;
use App\Models\Gambar;
use Illuminate\Support\Facades\File;

class GambarController extends Controller
{
    public function destroy($uuid, $id)
    {
        $spop   = Spop::where("uuid", $uuid)->first();
        $gambar = Gambar::where("id", $id)->first();

        if(empty($spop))    return abort(403);
        elseif(empty($gambar))  return abort(403);
        else{
            $image_path = public_path() . "/storage/data_spop/$gambar->nama";  // Value is not URL but directory file path
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $gambar->delete();
            return redirect()->back()->with("msg", "gambar berhasil di hapus");
        }
    }
}
