<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Animal;

class EleveurController extends Controller
{
   public function dashboard()
{
    $user = Auth::user();
    $animals = \App\Models\Animal::where('eleveur_id', auth()->id())->latest()->get();
    return view('eleveur.dashboard', compact('user', 'animals'));
}

    // update localisation pour trover des veterinaire 
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
    // les consultations d'un elever 
    $consultations = \App\Models\Consultation::with('veterinaire')
        ->where('eleveur_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('eleveur.isticharati', compact('consultations'));
}
public function animalsIndex()
{
    $animals = Animal::where('eleveur_id', auth()->id())->latest()->get();
    return view('eleveur.animale', compact('animals')); 
}

// enregistrer new animale dans la base de donnee 
public function storeAnimal(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|string',
        'identification_code' => 'nullable|string',
        'age' => 'required|string',
    ]);

    Animal::create([
        'type' => $validated['type'],
        'identification_code' => $validated['identification_code'],
        'age' => $validated['age'],
        'eleveur_id' => auth()->id(), // ربط الحيوان بالمربي الحالي
    ]);

    return back()->with('success', 'تم إضافة الحيوان إلى قطيعك بنجاح.');
}

// update les donnee d'un animale 
public function updateAnimal(Request $request, $id)
{
    $animal = Animal::where('eleveur_id', auth()->id())->findOrFail($id);

    $validated = $request->validate([
        'type' => 'required|string',
        'identification_code' => 'nullable|string',
        'age' => 'required|string',
    ]);

    $animal->update($validated);

    return back()->with('success', 'تم تحديث بيانات الحيوان بنجاح.');
}

// supprimer animales 
public function destroyAnimal($id)
{
    $animal = Animal::where('eleveur_id', auth()->id())->findOrFail($id);
    $animal->delete();

    return back()->with('success', 'تم حذف الحيوان من السجل بنجاح.');
}
public function confirmConsultation(Request $request, $id)
{
    $request->validate([
        'user_decision' => 'required|in:confirmed,declined'
    ]);

    // جلب الاستشارة الحالية لمعرفة تفاصيل المجموعة
    $currentConsultation = \App\Models\Consultation::where('eleveur_id', auth()->id())->findOrFail($id);

    // تحديد الكلمة المناسبة لقاعدة البيانات بناءً على قرار الفلاح
    // إذا وافق الفلاح نكتب 'accepted' لكي يفهمها لوحة الطبيب وتظهر "مقبولة"
    $dbStatus = $request->user_decision == 'confirmed' ? 'accepted' : 'declined';

    // تحديث المجموعة كاملة في قاعدة البيانات لكي تتغير عند الطرفين معاً
    \App\Models\Consultation::where('eleveur_id', auth()->id())
        ->where('date_demande', $currentConsultation->date_demande)
        ->where('motif', $currentConsultation->motif)
        ->update([
            'status' => $dbStatus
        ]);

    $message = $request->user_decision == 'confirmed' ? 'تم قبول وتثبيت الموعد بنجاح!' : 'تم رفض الموعد المقترح.';
    
    return back()->with('success', $message);
}
}