<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProduitController extends Controller
{
    use AuthorizesRequests;

    // 📊 عرض + بحث + BONUS (غير منتجات الموزع)
    public function index(Request $request)
    {
        $search = $request->search;

        $produits = Store::where('distributeur_id', auth()->id()) // 🔥 BONUS
            ->with('produit')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('produit', function ($q) use ($search) {
                    $q->where('nom', 'like', '%' . $search . '%');
                });
            })
            ->get();

        $allProduits = Produit::all();

        return view('produits.index', compact('produits', 'allProduits'));
    }

    // ➕ ajouter
    public function store(Request $request)
    {
        // ✅ validation
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite'   => 'required|numeric|min:1',
            'prix'       => 'required|numeric|min:0',
            'date_exp'   => 'nullable|date',
        ]);

        $this->authorize('create', Store::class);

        Store::create([
            'produit_id'      => $request->produit_id,
            'distributeur_id' => $distributeur()->id(), //  مربوط بالموزع
            'quantite'        => $request->quantite,
            'prix'            => $request->prix,
            'date_exp'        => $request->date_exp,
        ]);

        return redirect()->back()->with('success', 'تمت الإضافة بنجاح');
    }

    // ✏️ edit
    public function edit($id)
    {
        $store = Store::findOrFail($id);

        $this->authorize('update', $store);

        return view('produits.edit', compact('store'));
    }

    // 🔄 update
    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $this->authorize('update', $store);

        // validation
        $request->validate([
            'quantite' => 'required|numeric|min:1',
            'prix'     => 'required|numeric|min:0',
            'date_exp' => 'nullable|date',
        ]);

        $store->update([
            'quantite' => $request->quantite,
            'prix'     => $request->prix,
            'date_exp' => $request->date_exp,
        ]);

        return redirect()->route('produits.index')
                         ->with('success', 'تم التعديل بنجاح');
    }

    // ❌ delete
    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        $this->authorize('delete', $store);

        $store->delete();

        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}