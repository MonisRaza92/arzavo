<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        
        return view('tenant.admin.dashboard.index');
    }
}
