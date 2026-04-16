<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DistributeurController extends Controller
{
    public function dashboard()
{
    return view('distributeur.dashboard');
}
}
