<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth; // ✅ مهم

class ConsultationController extends Controller
{
    // 👨‍⚕️ Dashboard vétérinaire
    public function indexVet()
    {
        $consultations = Consultation::with('eleveur')
            ->where('veterinaire_id', Auth::id())
            ->latest()
            ->get();

        return view('veterinaire.consultations', compact('consultations'));
    }

    // 👨‍🌾 إنشاء طلب
    public function store(Request $request)
    {
        $request->validate([
            'veterinaire_id' => 'required',
            'motif' => 'required'
        ]);

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
        $request->validate([
            'date_consultation' => 'required|date',
            'degree' => 'required'
        ]);

        $consultation = Consultation::findOrFail($id);

        $consultation->update([
            'date_consultation' => $request->date_consultation,
            'degree' => $request->degree
        ]);

        return back()->with('success', 'تم التحديث');
    }

    // 🗑️ حذف
    public function destroy($id)
    {
        Consultation::findOrFail($id)->delete();

        return back()->with('success', 'تم الحذف');
    }
}