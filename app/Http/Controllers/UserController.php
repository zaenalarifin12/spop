<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class UserController extends Controller
{
    public function show($nip)
    {
        $user = User::where("nip", $nip)->first();

        return view("profile.show", compact("user"));
    }
}
