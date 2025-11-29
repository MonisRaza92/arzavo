<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;

class HomeController
{
    public function index()
    {
        return view('arzavo.home.index');
    }
}
