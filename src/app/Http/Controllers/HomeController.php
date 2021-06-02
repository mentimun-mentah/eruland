<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome(Request $request)
    {
        return view('welcome', ['query' => json_encode(array("q" => $request->q, "category" => $request->category))]);
    }

}
