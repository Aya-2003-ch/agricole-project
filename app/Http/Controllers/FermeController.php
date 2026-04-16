<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FermeController extends Controller
{
    public function dashboard()
{
    return view('ferme.dashboard');
}
}
