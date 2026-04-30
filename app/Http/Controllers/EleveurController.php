<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleveur; // تأكدي بلي المودل مسمي هكذا

class EleveurController extends Controller
{
    // 1. هادي تفتح برك الصفحة
    public function dashboard()
    {
        return view('eleveur.dashboard');
    }

    // 2. هادي هي "المهمة" اللي تحفظ المعلومات والخريطة
    public function store(Request $request)
    {
        // نتحققو بلي المعلومات كامل كاينين
        $request->validate([
            'nom' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // نكرييو مزرعة جديدة في الداتابيز
        $eleveur = new Eleveur();
        $eleveur->nom = $request->nom;
        $eleveur->localisation = $request->localisation ?? 'غير محدد';
        
        // هنا نربطو الإحداثيات اللي جاو من الخريطة
        $eleveur->latitude = $request->latitude;
        $eleveur->longitude = $request->longitude;

        $eleveur->save();

        return redirect()->back()->with('success', 'تم حفظ موقع المزرعة بنجاح!');
    }
}