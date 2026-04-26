<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DistributeurController extends Controller
{
    public function dashboard()
{
    return view('distributeur.dashboard');
}
 public function profile()
{
    return view('distributeur.profile', ['user' => auth()->user()]);
}

}
