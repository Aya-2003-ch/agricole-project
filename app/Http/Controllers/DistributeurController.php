<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Commande;

class DistributeurController extends Controller
{
    public function dashboard()
{
    // جلب بيانات الموزع 
    $distributeur = \App\Models\Distributeur::where('user_id', auth()->id())->first();
      //حساب توتال المنتجات
    if ($distributeur) {
        $totalProduits = \App\Models\Store::where('distributeur_id', $distributeur->id)->count();
        $totalProduits = 0;
       
    }

    return view('distributeur.dashboard', compact('totalProduits'));
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
