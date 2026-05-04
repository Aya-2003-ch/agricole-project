<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 

class ConsultationController extends Controller
{
    // للفلاح: يعرض استشاراته + قائمة البياطرة (لحل مشكلة المتغير غير المعرف)
   public function index()
{
    // جلب البياطرة
    $veterinaires = User::where('role', 'veterinaire')->get();

    // جلب الاستشارات الخاصة بالفلاح الحالي يدوياً
    $consultations = Consultation::where('eleveur_id', Auth::id())
                        ->with('veterinaire') // جلب بيانات الطبيب لتقليل الضغط على قاعدة البيانات
                        ->latest()
                        ->get(); 

    // تمرير البيانات (تأكد أن الأسماء مطابقة تماماً لما تستخدمه في Blade)
    return view('eleveur.consultations', compact('veterinaires', 'consultations'));
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

    // جلب البياطرة القريبين (الرادار التفاعلي للخريطة)
    public function getNearbyVets(Request $request) 
    {
        $lat = $request->lat ?? Auth::user()->latitude;
        $lng = $request->lng ?? Auth::user()->longitude;

        if (!$lat || !$lng) {
            return response()->json([]); 
        }

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
        'veterinaire_id' => 'required',
        'motif' => 'required',
    ]);

    Consultation::create([
        'eleveur_id' => auth()->id(),
        'veterinaire_id' => $request->veterinaire_id,
        'date_demande' => now(),
        'motif' => $request->motif,
        'status' => 'pending'
    ]);

    return back()->with('success', 'تم إرسال الطلب');
}
public function updateStatus(Request $request, $id)
{
    $consultation = Consultation::findOrFail($id);

    $consultation->status = $request->status; // accepted / rejected
    $consultation->save();

    return response()->json(['success' => true]);
}
public function update(Request $request, $id)
{
    $consultation = Consultation::findOrFail($id);

    $consultation->update([
        'date_consultation' => $request->date_consultation,
        'degree' => $request->degree,
        'diagnostique' => $request->diagnostique,
        'status' => 'accepted' // كي يكملها تتأكد
    ]);

    return response()->json(['success' => true]);
}
}