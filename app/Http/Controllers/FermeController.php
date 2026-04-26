<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FermeAgricole; // تأكدي بلي المودل مسمي هكذا

class FermeController extends Controller
{
    // 1. هادي تفتح برك الصفحة
    public function dashboard()
    {
        return view('ferme.dashboard');
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
        $ferme = new FermeAgricole();
        $ferme->nom = $request->nom;
        $ferme->localisation = $request->localisation ?? 'غير محدد';
        
        // هنا نربطو الإحداثيات اللي جاو من الخريطة
        $ferme->latitude = $request->latitude;
        $ferme->longitude = $request->longitude;

        $ferme->save();

        return redirect()->back()->with('success', 'تم حفظ موقع المزرعة بنجاح!');
    }
}