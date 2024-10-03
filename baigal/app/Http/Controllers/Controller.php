<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Stevebauman\Location\Facades\Location as FacadesLocation;

class Controller extends BaseController
{
    public function index()
    {
        return view('admin.post');
    }
}
