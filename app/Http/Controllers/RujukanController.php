<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rujukan;
use Yajra\DataTables\DataTables;

class RujukanController extends Controller
{
    public function json()
    {
        return DataTables::of(Rujukan::all())
        ->addColumn('action', function($row) {
            return '<a href="/pemutakhiran/create/'. $row->uuid .'" class="btn btn-primary">Edit</a>';
        })->make(true);
    }

    public function index()
    {
        return view("rujukan.index");
    }
}
