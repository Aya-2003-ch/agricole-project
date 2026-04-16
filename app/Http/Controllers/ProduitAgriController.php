<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitAgriController extends Controller
{
    // affichage + recherche
    public function index(Request $request)
    {
        $search = $request->search;

        $produits = Store::with('produit')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('produit', function ($q) use ($search) {
                    $q->where('nom', 'like', '%' . $search . '%');
                });
            })
            ->get();

        $allProduits = Produit::all();

        return view('produits.index', compact('produits', 'allProduits'));
    }

    // ajouter
    public function store(Request $request)
    {
        $this-> authorize('create', Store::class);
        Store::create([
            'produit_id' => $request->produit_id,
            'distributeur_id' => auth()->id(),
            'quantite' => $request->quantite,
            'prix' => $request->prix,
            'date_exp' => $request->date_exp,
        ]);

        return redirect()->back();
    }

    // edit
    public function edit($id)
    {
        $produit = Store::findOrFail($id);
        return view('produits.edit', compact('produit'));
    }

    // update
    public function update(Request $request, $id)
    { 
        $store=Store::findOrFail($id);
        $this-> authorize('update', Store::class);
        $produit = Store::findOrFail($id);

        $produit->update([
            'quantite' => $request->quantite,
            'prix' => $request->prix,
            'date_exp' => $request->date_exp,
        ]);

        return redirect()->route('produit_agri.index');
    }

    // delete
    public function destroy($id)
    {
         $store=Store::findOrFail($id);
        $this-> authorize('delete', Store::class);
        $store ->delete();
        return redirect()->back();
    }
}