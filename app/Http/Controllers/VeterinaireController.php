<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Commande; 
use App\Models\Produit; // يمثل الأدوية المعروضة من الموزعين
use Illuminate\Http\Request;

class VeterinaireController extends Controller
{
    // 1. الداشبورد: عرض الاستشارات والطلبات التي قام بها البيطري
    public function dashboard()
    {
        // استشارات الفلاحين الموجهة لهذا البيطري
        $consultations = Consultation::where('veterinaire_id', auth()->id())
            ->with('user') 
            ->latest()
            ->get();
        
        // عدد طلبات الأدوية التي أرسلها البيطري للموزعين وما زالت قيد الانتظار
        $newOrdersCount = Commande::where('id_user', auth()->id()) // البيطري هنا هو صاحب الطلب
            ->where('statut', 'pending')
            ->count(); 

        return view('veterinaire.dashboard', compact('consultations', 'newOrdersCount'));
    }

    // 2. صفحة البحث عن الأدوية (عرض فقط بدون تعديل)
    public function medicines(Request $request)
    {
        // البيطري يتصفح الأدوية المتوفرة عند الموزعين
        $query = Produit::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $medicines = $query->latest()->get(); 
        
        return view('veterinaire.medicines', compact('medicines'));
    }

    // 3. دالة إرسال طلب شراء دواء من موزع
    public function placeOrder(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
        ]);

        // إنشاء طلب جديد في جدول Commandes
        Commande::create([
            'user_id' => auth()->id(), // معرف البيطري
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'statut' => 'pending',
            'date_commande' => now(),
        ]);

        return back()->with('success', 'تم إرسال طلب الدواء للموزع بنجاح');
    }

    // 4. عرض تاريخ طلبات الأدوية التي قام بها البيطري
    public function myOrders()
    {
        $commandes = Commande::where('user_id', auth()->id())
            ->with('produit')
            ->latest()
            ->get();
            
        return view('veterinaire.my_orders', compact('commandes'));
    }

    // 5. التبليغ عن الأوبئة (صلاحية خاصة بالبيطري)
    public function report()
    {
        return view('veterinaire.report');
    }

    public function sendReport(Request $request)
    {
        $request->validate([
            'disease_name' => 'required|string',
            'description' => 'required',
            'location' => 'required'
        ]);

        // منطق إرسال التبليغ...
        return redirect()->route('veterinaire.dashboard')->with('success', 'تم التبليغ بنجاح');
    }

    // 6. الاستشارات والبروفايل والشات (تظل كما هي)
    public function consultations() 
    { 
        $consultations = Consultation::where('veterinaire_id', auth()->id())->with('user')->latest()->get();
        return view('veterinaire.consultations', compact('consultations')); 
    }

    public function profile() { return view('veterinaire.profile', ['user' => auth()->user()]); }
    public function chats() { return view('veterinaire.chats'); }
}