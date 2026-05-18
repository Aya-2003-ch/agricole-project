<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Consultation; // استدعاء الموديل مباشرة لتسهيل الكود
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * جلب الأطباء القريبين بناءً على إحداثيات يتم إرسالها عبر AJAX
     */
    public function getNearbyVets(Request $request)
    {
        $lat = $request->lat ?? Auth::user()->latitude;
        $lng = $request->lng ?? Auth::user()->longitude;

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Location not provided'], 400);
        }

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

    /**
     * 🛠️ تعديل دالة الحفظ عند الفلاح لدعم اختيار حيوانات متعددة
     * تقوم بتقسيم الطلب المتعدد إلى أسطر منفصلة لكل حيوان
     */
    public function store(Request $request)
    {
        $request->validate([
            'veterinaire_id' => 'required|exists:users,id',
            'animal_ids'     => 'required|array', // التأكد من إرسال مصفوفة حيوانات
            'animal_ids.*'   => 'exists:animals,id',
            'motif'          => 'required|string|min:5',
        ]);

        // تكرار الحفظ لكل حيوان تم تحديثه بالـ Checkbox لإنشاء سطر مستقل
        foreach ($request->animal_ids as $animalId) {
            Consultation::create([
                'eleveur_id'     => auth()->id(),
                'veterinaire_id' => $request->veterinaire_id,
                'animal_id'      => $animalId, // 👈 ربط كل سطر بحيوان منفصل
                'motif'          => $request->motif,
                'date_demande'   => now(),
                'status'         => 'pending', 
            ]);
        }

        return redirect()->back()->with('success', 'تم إرسال طلب الاستشارة لجميع الحيوانات المحددة بنجاح!');
    }

    /**
     * 🛠️ تحديث دالة العرض للطبيب ليشمل بيانات الحيوان (with) لمنع خطأ "غير محدد"
     */
    public function indexVet()
    {
        $consultations = Consultation::with(['eleveur', 'animal']) // 👈 جلب الفلاح والحيوان معاً
            ->where('veterinaire_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('veterinaire.consultations', compact('consultations'));
    }

    /**
     * تحديث حالة الاستشارة من طرف الطبيب (تحديد موعد أو رفض الطلب)
     */
    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($consultation->veterinaire_id != auth()->id()) {
            return back()->with('error', 'غير مسموح لك بهذا الإجراء');
        }

        $consultation->update([
            'status'            => $request->status, // 'accepted' أو 'rejected'
            'date_consultation' => $request->date_consultation, 
        ]);

        $message = $request->status == 'accepted' ? 'تم قبول الاستشارة وتحديد الموعد المبدئي.' : 'تم رفض الطلب.';

        return back()->with('success', $message);
    }

    /**
     * دالة سريعة لتغيير الحالة مباشرة
     */
    public function updateStatus(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);
        
        $consultation->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'تم تغيير حالة الاستشارة');
    }

    /**
     * قرار الفلاح بتأكيد الموعد المقترح من الطبيب أو رفضه
     */
    public function confirmReservation(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        if ($request->user_decision == 'confirmed') {
            $consultation->update(['status' => 'confirmed']);
            $msg = "تم تأكيد قبولك للموعد بنجاح!";
        } else {
            $consultation->update(['status' => 'declined']);
            $msg = "تم رفض الموعد المقترح.";
        }

        return back()->with('success', $msg);
    }

    /**
     * 🛠️ تحديث دالة العرض للفلاح لتجلب أيضاً بيانات الحيوان المصاحب للاستشارة
     */
    public function indexEleveur()
    {
        $consultations = Consultation::with(['veterinaire', 'animal']) // 👈 جلب الطبيب والحيوان
            ->where('eleveur_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('eleveur.isticharati', compact('consultations'));
    }

    /**
     * 🛠️ إضافة واجهة حفظ التقرير الطبي (التشخيص والدواء) لكل حيوان بعد الفحص الميداني
     */
    public function saveReport(Request $request, $id)
    {
        $request->validate([
            'diagnostique' => 'required|string|max:1000',
            'traitement'   => 'required|string|max:1000', // الوصفة الطبية
        ]);

        $consultation = Consultation::findOrFail($id);

        if ($consultation->veterinaire_id != auth()->id()) {
            return back()->with('error', 'غير مسموح لك بتعديل هذا التقرير.');
        }

        $consultation->update([
            'diagnostique' => $request->diagnostique,
            'traitement'   => $request->traitement, // تأكدي من إضافتها لـ $fillable في موديل Consultation
            'status'       => 'completed', // تحويل الحالة تلقائياً إلى "مكتملة"
        ]);

        return redirect()->back()->with('success', 'تم تسجيل التشخيص الطبي والوصفة بنجاح لهذا الحيوان.');
    }

    /**
     * حذف طلب الاستشارة نهائياً
     */
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return redirect()->back()->with('success', 'تم حذف طلب الاستشارة بنجاح نهائياً.');
    }
}