<?php

namespace App\Http\Controllers\Tenant\User;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        echo "User Dashboard";
    }
}
