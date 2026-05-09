<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * جلب الأطباء القريبين بناءً على إحداثيات يتم إرسالها عبر AJAX
     */
    public function getNearbyVets(Request $request)
    {
        // استقبال الإحداثيات من الطلب (Request)
        // إذا لم توجد في الطلب، نأخذ المسجلة في حساب المستخدم
        $lat = $request->lat ?? Auth::user()->latitude;
        $lng = $request->lng ?? Auth::user()->longitude;

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Location not provided'], 400);
        }

        /**
         * شرح التعديل:
         * 1. استخدمنا ST_Distance_Sphere وهي الأدق جغرافياً.
         * 2. الترتيب داخل POINT هو (Longitude ثم Latitude) وهذا هو المعيار العالمي.
         * 3. القسمة على 1000 لتحويل النتيجة من أمتار إلى كيلومترات.
         */
        $vets = User::where('role', 'veterinaire')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'name', 'address', 'latitude', 'longitude')
            ->selectRaw("
                (ST_Distance_Sphere(
                    point(longitude, latitude), 
                    point(?, ?)
                ) / 1000) AS distance
            ", [$lng, $lat])
            ->orderBy('distance')
            ->get();

        return response()->json($vets);
    }

    /**
     * تحديث موقع المربي (المزرعة)
     */
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

        return back()->with('success', 'تم تحديث موقع المزرعة بنجاح!');
    }
    public function store(Request $request)
{
    $request->validate([
        'veterinaire_id' => 'required|exists:users,id',
        'motif' => 'required|string|min:5',
    ]);

    \App\Models\Consultation::create([
        'eleveur_id' => auth()->id(),
        'veterinaire_id' => $request->veterinaire_id,
        'motif' => $request->motif,
        'date_demande' => now(), // الحقل موجود في جدولك
        'status' => 'pending',   // القيمة الافتراضية
    ]);

    return redirect()->back()->with('success', 'تم إرسال طلبك للطبيب! يمكنك متابعة الحالة من صفحة الاستشارات.');
}
  // 1. دالة عرض صفحة الاستشارات للطبيب
public function indexVet()
{
    // جلب كل الاستشارات الخاصة بهذا الطبيب مع بيانات المربي (الفلاح)
    $consultations = \App\Models\Consultation::with('eleveur')
        ->where('veterinaire_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('veterinaire.consultations', compact('consultations'));
}

// 2. دالة تحديث حالة الاستشارة (قبول أو رفض أو تحديد موعد)
public function update(Request $request, $id)
{
    $consultation = \App\Models\Consultation::findOrFail($id);

    // التأكد أن الطبيب هو صاحب الاستشارة
    if ($consultation->veterinaire_id != auth()->id()) {
        return back()->with('error', 'غير مسموح لك بهذا الإجراء');
    }

    // تحديث البيانات بناءً على ما أرسله الطبيب
    $consultation->update([
        'status' => $request->status, // 'accepted' أو 'rejected'
        'date_consultation' => $request->date_consultation, // التاريخ المختار
        'diagnostique' => $request->diagnostique, // ملاحظات الطبيب
    ]);

    $message = $request->status == 'accepted' ? 'تم قبول الاستشارة وتحديد الموعد' : 'تم رفض الطلب';

    return back()->with('success', $message);
}
  public function updateStatus(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);
        
        $consultation->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'تم تغيير حالة الاستشارة');
    }
    public function confirmReservation(Request $request, $id)
{
    $consultation = \App\Models\Consultation::findOrFail($id);

    if ($request->user_decision == 'confirmed') {
        // الفلاح وافق على الموعد
        $consultation->update(['status' => 'confirmed']);
        $msg = "تم تأكيد الموعد بنجاح!";
    } else {
        // الفلاح رفض الموعد المقترح
        $consultation->update(['status' => 'declined']);
        $msg = "تم رفض الموعد المقترح.";
    }

    return back()->with('success', $msg);
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