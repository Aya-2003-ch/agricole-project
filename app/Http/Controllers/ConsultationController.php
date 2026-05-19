<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Consultation; 
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * جلب الأطباء القريبين
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
     * دالة الحفظ: تفكيك المصفوفة وحفظ كل حيوان في سطر منفصل
     */
    public function store(Request $request)
    {
        $request->validate([
            'veterinaire_id' => 'required|exists:users,id',
            'motif'          => 'required|string|max:500',
            'animal_ids'     => 'required|array',
            'animal_ids.*'   => 'exists:animals,id',
        ]);

        try {
            $dateDemande = now()->format('Y-m-d H:i:s');
            $eleveurId = auth()->id();

            foreach ($request->animal_ids as $animalId) {
                Consultation::create([
                    'eleveur_id'     => $eleveurId,
                    'veterinaire_id' => $request->veterinaire_id,
                    'animal_id'      => $animalId,
                    'motif'          => $request->motif,
                    'date_demande'   => $dateDemande,
                    'status'         => 'pending',
                ]);
            }

            return redirect()->back()->with('success', 'تم إرسال طلب الاستشارة بنجاح إلى الطبيب البيطري!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', "خطأ أثناء الحفظ: " . $e->getMessage());
        }
    }

    /**
     * عرض الاستشارات الخاصة بالطبيب البيطري
     */
    public function indexVet()
    {
        $vetId = auth()->id();

        $consultations = Consultation::with(['eleveur', 'animal'])
            ->where('veterinaire_id', $vetId)
            ->orderByRaw('COALESCE(date_demande, created_at) DESC')
            ->get();

        return view('veterinaire.consultations', compact('consultations'));
    }

    /**
     * عرض الاستشارات الخاصة بالفلاح
     */
    public function indexEleveur()
    {
        $consultations = Consultation::with(['veterinaire', 'animal'])
            ->where('eleveur_id', auth()->id())
            ->orderByRaw('COALESCE(date_demande, created_at) DESC')
            ->get();

        return view('eleveur.isticharati', compact('consultations'));
    }

    /**
     * تحديث التشخيص الطبي والتقرير (للاشتشارات المقبولة فقط)
     */
    public function update(Request $request, $id)
    {
        if ($request->has('data')) {
            foreach ($request->data as $conId => $values) {
                $con = Consultation::find($conId);
                if ($con && $con->veterinaire_id == auth()->id()) {
                    $con->update([
                        'diagnostique' => $values['diagnostique'],
                        'traitement' => $values['traitement']
                    ]);
                }
            }
            return back()->with('success', 'تم حفظ التقارير الطبية والتشخيص بنجاح.');
        }
        return back()->with('error', 'لم يتم إرسال أي بيانات للتشخيص.');
    }

    /**
     * 🛠️ تحديث الحالة واقتراح الموعد من طرف الطبيب البيطري
     * تم تعديلها لحل مشكلة الموعد الأصفار وتداخل الحالات المقبولة والمرفوضة
     */
    public function updateStatus(Request $request, $id)
    {
        $baseConsultation = Consultation::findOrFail($id);
        
        $updateData = [];

        // إذا أرسل البيطري موعداً مقترحاً (الحالة تبقى pending قيد الانتظار حتى يقبل المربي)
        if ($request->has('date_consultation') && $request->filled('date_consultation')) {
            $updateData['date_consultation'] = \Carbon\Carbon::parse($request->date_consultation)->format('Y-m-d H:i:s');
            $message = 'تم إرسال الموعد المقترح إلى الفلاح، وفي انتظار تأكيده.';
        }

        // إذا كان الإجراء رفضاُ مباشراً من البيطري تلتغي الاستشارة فوراً
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
            $message = $request->status == 'rejected' ? 'تم رفض طلب الاستشارة بنجاح.' : 'تم تحديث الحالة.';
        }

        // تحديث دقيق للمجموعة الحالية فقط بالاعتماد على الـ created_at لمنع تداخل الاستشارات الأخرى
        Consultation::where('eleveur_id', $baseConsultation->eleveur_id)
            ->where('motif', $baseConsultation->motif)
            ->where('created_at', $baseConsultation->created_at)
            ->update($updateData);

        return back()->with('success', $message);
    }

    /**
     * قرار الفلاح بتأكيد الموعد المقترح من الطبيب
     */
    public function confirmReservation(Request $request, $id)
    {
        $baseConsultation = Consultation::findOrFail($id);
        
        $newStatus = $request->user_decision == 'confirmed' ? 'accepted' : 'rejected';

        Consultation::where('eleveur_id', $baseConsultation->eleveur_id)
            ->where('motif', $baseConsultation->motif)
            ->where('created_at', $baseConsultation->created_at)
            ->update(['status' => $newStatus]);

        $msg = $request->user_decision == 'confirmed' ? "تم تأكيد قبولك للموعد بنجاح!" : "تم رفض الموعد المقترح.";
        return back()->with('success', $msg);
    }

    /**
     * حذف طلب الاستشارة بالكامل
     */
    public function destroy(Request $request, $id)
    {
        $baseConsultation = Consultation::findOrFail($id);

        Consultation::where('eleveur_id', $baseConsultation->eleveur_id)
            ->where('motif', $baseConsultation->motif)
            ->where('created_at', $baseConsultation->created_at)
            ->delete();

        return back()->with('success', 'تم حذف طلب الاستشارة المحدد بنجاح.');
    }
}