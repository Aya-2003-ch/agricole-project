<?php

namespace App\Http\Controllers;
use App\Models\Store;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function Search(Request $request)
{
    $search = $request->search;

    $results = Store::with(['produit', 'distributeur'])
        ->whereHas('produit', function ($q) use ($search) {
            $q->where('nom', 'like', '%' . $search . '%');
        })
        ->get();

    return view('search.results', compact('results'));
}
}
