<?php

namespace App\Http\Controllers;
use App\Models\Consultation;
use Illuminate\Http\Request;

class VeterinaireController extends Controller
{
    public function dashboard()
{
    $consultations = Consultation::with('user')->latest()->get();
    return view('veterinaire.dashboard',compact('consultations'));
}

public function consultations()
{
    return view('veterinaire.consultations');
}
public function profile()
{
    return view('veterinaire.profile',[
        'user' => auth()->user()
    ]);
}
}
