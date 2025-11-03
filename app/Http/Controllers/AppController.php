<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController
{
    public function index()
    {
        return view('arzavo.index');
    }
}
