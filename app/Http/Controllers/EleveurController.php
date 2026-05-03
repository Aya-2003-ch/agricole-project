<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleveur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EleveurController extends Controller
{
    // 1. عرض لوحة التحكم
    public function dashboard()
    {
        // جلب بيانات الفلاح المرتبطة بالمستخدم الحالي
        $eleveur = Eleveur::where('user_id', Auth::id())->first();
        return view('eleveur.dashboard', compact('eleveur'));
    }

    // 2. دالة البحث (تستخدم موقع الفلاح من جدول eleveurs)
    public function search(Request $request)
{
    $searchQuery = $request->input('medicine');
    $eleveur = Eleveur::where('user_id', Auth::id())->first();

    // إحداثيات الفلاح
    $lat = $eleveur->latitude ?? 36.4621;
    $lng = $eleveur->longitude ?? 7.4311;

    $results = DB::table('stores')
        // الربط الأول: ربط المتجر بالموزع للحصول على الموقع والاسم
        ->join('users', 'stores.distributeur_id', '=', 'users.id')
        // الربط الثاني: ربط المتجر بجدول المنتجات للحصول على اسم الدواء
        ->join('produits', 'stores.produit_id', '=', 'produits.id') 
        ->select(
            'users.name as distributeur_name',
            'users.address',
            'users.latitude as lat',
            'users.longitude as lng',
            'produits.nom as medicine_name', // هنا نجلب الاسم من جدول produit (تأكدي أن العمود اسمه nom)
            'stores.prix', // السعر من جدول المتجر
            DB::raw("ROUND(6371 * acos(cos(radians($lat)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(users.latitude))), 1) AS distance")
        )
        // البحث الآن يكون في عمود الاسم داخل جدول produit
        ->where('produits.nom', 'LIKE', '%' . $searchQuery . '%') 
        ->orderBy('distance', 'asc')
        ->get();

    return view('eleveur.dashboard', compact('results', 'searchQuery', 'eleveur'));
}

    // 3. تحديث الموقع في جدول eleveurs
    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);

        // تحديث السجل الموجود في جدول eleveurs
        $eleveur = Eleveur::where('user_id', Auth::id())->first();
        
        if ($eleveur) {
            $eleveur->update([
                'latitude' => $request->lat,
                'longitude' => $request->lng,
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث موقع المزرعة في جدول البيانات!');
    }
}