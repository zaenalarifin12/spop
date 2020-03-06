<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show($nip)
    {
        $user = User::where("nip", $nip)->first();

        return view("profile.show", compact("user"));
    }

    public function edit($nip)
    {
        $user = User::where("nip", $nip)->first();
        if(empty($user)) abort(404);

        return view("profile.edit", compact("user"));
    }

    public function update(Request $request, $nip)
    {
        $user = User::where("nip", $nip)->first();

        if(Auth::user()->role == 1){
            $user->update([
                "name"      => $request->name,
                "nip"       => $request->nip,
                "instansi"  => $request->instansi,
                "password"  => Hash::make($request->password)
            ]);
        }
        // menguji kesamaan user login dan pengedit
        elseif(Auth::user()->nip != $user->nip){
            abort(403);
        }else{
            $user->update([
                "name"      => $request->name,
                "nip"       => $request->nip,
                "instansi"  => $request->instansi
            ]);
        }
    
        return redirect("/profile/$user->nip")->with("profil berhasil di ubah");
    }
}
