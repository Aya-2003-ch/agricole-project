<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EleveurController extends Controller
{
    // عرض لوحة تحكم المربي
    public function dashboard()
    {
        // نجلب بيانات المربي الحالي
        $user = Auth::user();
        
        // يمكننا هنا جلب عدد استشاراته النشطة لعرضها في الإحصائيات
        return view('eleveur.dashboard', compact('user'));
    }

    // تحديث الموقع الجغرافي (ضروري لإيجاد أقرب بيطري)
    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = Auth::user();
        $user->update([
            'latitude' => $request->lat,
            'longitude' => $request->lng,
        ]);

        return redirect()->back()->with('success', 'تم تحديث موقعك بنجاح! يمكنك الآن رؤية الأطباء الأقرب إليك.');
    }
    public function indexEleveur()
{
    // جلب استشارات الفلاح الحالي مع بيانات الطبيب البيطري
    $consultations = \App\Models\Consultation::with('veterinaire')
        ->where('eleveur_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('eleveur.isticharati', compact('consultations'));
}
}