<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rujukan;

class RujukanController extends Controller
{
    
    public function index()
    {
        $rujukans = Rujukan::get();
        return view("rujukan.index", compact("rujukans"));
    }
}
