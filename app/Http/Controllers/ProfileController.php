<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
}
