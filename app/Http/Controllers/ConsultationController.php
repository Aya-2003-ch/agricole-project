<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    // 👨‍⚕️ Dashboard vétérinaire
    public function indexVet()
    {
        $consultations = Consultation::where('veterinaire_id', Auth::id())
            ->with('eleveur')
            ->latest()
            ->get();

        return view('veterinaire.consultations', compact('consultations'));
    }

    // 👨‍🌾 إنشاء طلب
    public function store(Request $request)
    {
        Consultation::create([
            'eleveur_id' => Auth::id(),
            'veterinaire_id' => $request->veterinaire_id,
            'date_demande' => now(),
            'motif' => $request->motif
        ]);

        return back()->with('success', 'تم إرسال الطلب');
    }

    // 👨‍⚕️ تأكيد الاستشارة
    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $consultation->update([
            'date_consultation' => $request->date_consultation,
            'degree' => $request->degree
        ]);

        return back()->with('success', 'تم التحديث');
    }
    //  حذف (اختياري)
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return back()->with('success', 'تم الحذف');
    }
}
