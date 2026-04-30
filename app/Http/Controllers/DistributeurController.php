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
public function store(Request $request)
    {
        Distributeur::create([
            'user_id' => auth()->id(),
            'nom' => $request->nom,
            'tele' => $request->tele,
            'localisation' => $request->localisation,
        ]);

        return redirect()->back()->with('success', 'تم إنشاء الموزع بنجاح');
    }

}
