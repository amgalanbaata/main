<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index()
    {
        if (Session::get('admin_token') != '') {
            return redirect('/admin/dashboard');
        } else {
            return view('admin.login', ['message' => '']);
        }
    }
}
