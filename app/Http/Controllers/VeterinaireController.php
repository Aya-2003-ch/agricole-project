<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VeterinaireController extends Controller
{
    public function dashboard()
{
    return view('veterinaire.dashboard');
}

public function consultations()
{
    return view('veterinaire.consultations');
}
}
