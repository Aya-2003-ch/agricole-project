<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Distributeur;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //  البحث
    public function search(Request $request)
{
    return \App\Models\Store::with(['produit', 'distributeur'])
        ->whereHas('produit', function ($q) use ($request) {
            $q->where('nom', 'like', '%' . $request->search . '%');
        })
        ->get()
        ->map(function ($item) {
            return [
                'nom' => $item->produit->nom ?? '',
                'address' => $item->distributeur->address ?? '',
                'prix' => $item->prix
            ];
        });
}
    //  الموزعين القريبين
    public function nearby()
    {
        return Distributeur::select('name', 'address', 'latitude', 'longitude')->get();
    }

    //  الإشعارات
    public function notifications()
    {
        $count = Consultation::where('status', 'pending')->count();

        return response()->json([
            'count' => $count
        ]);
    }
}