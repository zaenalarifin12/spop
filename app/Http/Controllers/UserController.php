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
            return '<a href="/profile/'.$row->nip.'/edit" class="btn btn-primary mr-1">Edit</a><a href="/profile/'.$row->nip.'" class="btn btn-info">Lihat</a>';
        })->make(true);
    }

}
