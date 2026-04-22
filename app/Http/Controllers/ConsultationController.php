<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    //  عرض جميع الاستشارات (للبيطري)
    public function index()
    {
        $consultations = Consultation::with('user')->latest()->get();

        return view('veterinaire.consultations', compact('consultations'));
    }

    //  إنشاء طلب جديد (الفلاح)
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        Consultation::create([
            'description' => $request->description,
            'status' => 'جديد',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'تم إرسال الطلب');
    }

    //  تحديث الحالة (البيطري يعالج الطلب)
    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $consultation->update([
            'status' => 'تمت المعالجة'
        ]);

        return back()->with('success', 'تمت معالجة الطلب');
    }

    //  حذف (اختياري)
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return back()->with('success', 'تم الحذف');
    }
}
