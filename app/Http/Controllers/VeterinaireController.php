<?php

namespace App\Http\Controllers; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function searchMedicines(Request $request)
{
    $searchQuery = $request->input('medicine');
    $veto = Auth::user(); // نستخدم موقع البيطري من جدول users

    // إحداثيات البيطري
    $lat = $veto->latitude ?? 36.4621;
    $lng = $veto->longitude ?? 7.4311;

    $results = DB::table('stores')
        ->join('users', 'stores.distributeur_id', '=', 'users.id')
        ->join('produits', 'stores.produit_id', '=', 'produits.id') 
        ->select(
            'users.name as distributeur_name',
            'users.address',
            'users.latitude as lat',
            'users.longitude as lng',
            'produits.nom as medicine_name', 
            'stores.prix',
            DB::raw("ROUND(6371 * acos(cos(radians($lat)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(users.latitude))), 1) AS distance")
        )
        ->where('produits.nom', 'LIKE', '%' . $searchQuery . '%') 
        ->orderBy('distance', 'asc') // ترتيب حسب الأقرب
        ->get();

    // نرجع لصفحة الداشبورد الخاصة بالبيطري مع النتائج
    return view('veterinaire.dashboard', compact('results', 'searchQuery'));
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
        $commandes = Commande::where('id_user', auth()->id())
            ->with('produit')
            ->latest()
            ->get();
            
        return view('veterinaire.orders', compact('commandes'));
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