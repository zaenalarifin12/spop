<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use DataTables;


class UserController extends Controller
{

    public function index()
    {
        return view("users.index");
    }

    public function json()
    {
        return DataTables::of(User::where("role",0)->get())
        ->addColumn('action', function($row) {
            return '<a href="/profile/edit" class="btn btn-primary">Edit</a>';
        })->make(true);
    }

}
