<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleveur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EleveurController extends Controller
{
    //  Dashboard
    public function dashboard()
    {
        $eleveur = Eleveur::where('user_id', Auth::id())->first();
        return view('eleveur.dashboard', compact('eleveur'));
    }

    //  البحث عن دواء
    public function search(Request $request)
    {
        $searchQuery = $request->input('medicine');

        // جلب الفلاح
        $eleveur = Eleveur::where('user_id', Auth::id())->first();

        //  حماية من الخطأ 
        if (!$eleveur) {
            return back()->with('error', 'لازم تحددي الموقع تاعك أولاً');
        }

        //  حماية إذا الموقع غير موجود
        if (!$eleveur->latitude || !$eleveur->longitude) {
            return back()->with('error', 'يرجى تحديد الموقع قبل البحث');
        }

        $lat = $eleveur->latitude;
        $lng = $eleveur->longitude;

        $results = DB::table('stores')
            ->join('users', 'stores.distributeur_id', '=', 'users.id')
            ->join('produits', 'stores.produit_id', '=', 'produits.id')
            ->select(
                'users.name as distributeur_name',
                'users.address',
                'users.latitude as lat',
                'users.longitude as lng',
                'produits.nom as medicine_name',
                'stores.prix',
                DB::raw("ROUND(
                    6371 * acos(
                        cos(radians($lat)) 
                        * cos(radians(users.latitude)) 
                        * cos(radians(users.longitude) - radians($lng)) 
                        + sin(radians($lat)) 
                        * sin(radians(users.latitude))
                    ), 1
                ) AS distance")
            )
            //  البحث
            ->where(DB::raw('LOWER(produits.nom)'), 'LIKE', '%' . strtolower($searchQuery) . '%')

            //   فقط القريبين
            ->having('distance', '<=', 50)

            //  ترتيب: الأقرب ثم الأرخص
            ->orderBy('distance', 'asc')
            ->orderBy('stores.prix', 'asc')

            ->get();

        return view('eleveur.dashboard', compact('results', 'searchQuery', 'eleveur'));
    }

    //  تحديث الموقع
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $eleveur = Eleveur::where('user_id', Auth::id())->first();

        if ($eleveur) {
            $eleveur->update([
                'latitude' => $request->lat,
                'longitude' => $request->lng,
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث موقع المزرعة بنجاح!');
    }
}