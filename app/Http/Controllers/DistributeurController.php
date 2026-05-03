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
   public function updateProfile(Request $request)
{
    $user = Auth::user();

    // التحقق من البيانات (Validation)
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'telephone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
    ]);

    // تحديث البيانات في قاعدة البيانات
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'telephone' => $request->telephone,
        'address' => $request->address,
    ]);

    return redirect()->back()->with('success', 'تم تحديث بياناتك بنجاح ✅');
}

}
