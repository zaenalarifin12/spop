<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rujukan;


class GenerateUuidRujukanController extends Controller
{
    public function index()
    {
        $rujukan = Rujukan::where("uuid", null)->get();        

        foreach($rujukan as $item){
            $randomString = Str::random(40) .time();
            
            $item->update([
                "uuid" => $randomString
            ]);
        }
        return redirect("/home");
    }
}
