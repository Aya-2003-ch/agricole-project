<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 

class ConsultationController extends Controller
{
    // للفلاح: يعرض فقط استشاراته التي طلبها هو
    public function index()
    {
        $consultations = Consultation::with('veterinaire')
            ->where('eleveur_id', Auth::id()) 
            ->latest()
            ->get();

        return view('eleveur.consultations', compact('consultations'));
    }

    // للبيطري: يعرض فقط الطلبات التي أرسلت إليه
    public function indexVet()
    {
        $consultations = Consultation::with('eleveur')
            ->where('veterinaire_id', Auth::id()) 
            ->latest()
            ->get();

        return view('veterinaire.consultations', compact('consultations'));
    }

    /**
     * جلب البياطرة القريبين (الرادار التفاعلي)
     * تم التعديل ليستقبل الإحداثيات من الخريطة مباشرة عبر AJAX
     */
    public function getNearbyVets(Request $request) 
    {
        // 1. الأولوية للإحداثيات القادمة من الخريطة (عند السحب)، وإذا لم توجد نستخدم إحداثيات الفلاح المسجلة
        $lat = $request->lat ?? Auth::user()->latitude;
        $lng = $request->lng ?? Auth::user()->longitude;

        // إذا لم يتوفر موقع (لا في الطلب ولا في الملف الشخصي)
        if (!$lat || !$lng) {
            return response()->json([]); 
        }

        // 2. حساب المسافة باستخدام صيغة Haversine
        // جلب المستخدمين الذين دورهم بيطري فقط
        $vets = User::where('role', 'veterinaire')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("id, name, latitude, longitude, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->orderBy('distance')
            ->get();

        return response()->json($vets);
    }

    // حفظ طلب استشارة جديد
    public function store(Request $request)
    {
        $request->validate([
            'veterinaire_id' => 'required|exists:users,id',
            'motif' => 'required|string|max:500'
        ]);

        Consultation::create([
            'eleveur_id' => Auth::id(),
            'veterinaire_id' => $request->veterinaire_id,
            'date_demande' => now(),
            'motif' => $request->motif,
            'status' => 'pending'
        ]);

        return back()->with('success', 'تم إرسال طلب الاستشارة بنجاح ✅');
    }

    /**
     * تحديث موقع الفلاح رسمياً في قاعدة البيانات
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'latitude' => $request->lat,
            'longitude' => $request->lng,
        ]);

        return back()->with('success', 'تم تحديث موقع مزرعتك بنجاح 📍');
    }
}