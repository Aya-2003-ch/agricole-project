<?php

namespace App\Http\Controllers; 

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Consultation;
use App\Models\Commande; 
use App\Models\Produit; 
use App\Models\User; // أضفت هذا السطر لاستخدام موديل المستخدمين
use Illuminate\Http\Request;

class VeterinaireController extends Controller
{
    // 1. الداشبورد: عرض الاستشارات، التنبيهات، والخريطة
    public function dashboard()
    {
        // استشارات الفلاحين الموجهة لهذا البيطري
        $consultations = Consultation::where('veterinaire_id', auth()->id())
            ->with('user') 
            ->latest()
            ->take(5)
            ->get();
        
        // جلب كل الموزعين لعرضهم على الخريطة فوراً
        $allDistributors = User::where('role', 'distributeur')
            ->get(['name', 'latitude', 'longitude', 'address']);

        // حساب الإشعارات للطلبات (accepted/rejected) التي لم يراها البيطري بعد
        $orderNotifications = Commande::where('sender_id', auth()->id())
            ->whereIn('status', ['accepted', 'rejected'])
            ->where('is_seen', false)
            ->count(); 

        return view('veterinaire.dashboard', compact('consultations', 'orderNotifications', 'allDistributors'));
    }

    // 2. البحث عن الأدوية المتاحة عند الموزعين (Market)
    public function market(Request $request)
    {
        $searchQuery = $request->input('medicine'); // تأكدي أن الاسم في الفورم هو medicine

        $results = DB::table('produits')
            ->join('users', 'produits.user_id', '=', 'users.id')
            ->select(
                'users.id as distributeur_id',
                'users.name as distributeur_name',
                'users.address as distributeur_address',
                'users.latitude as lat', // مهم للخريطة
                'users.longitude as lng', // مهم للخريطة
                'produits.id as produit_id',
                'produits.nom as medicine_name',
                'produits.prix as prix'
            )
            ->where('produits.nom', 'LIKE', '%' . $searchQuery . '%') 
            ->latest('produits.created_at')
            ->get();

        // نحتاج أيضاً لجلب الموزعين في صفحة النتائج لتبقى الخريطة تعمل
        $allDistributors = User::where('role', 'distributeur')->get(['name', 'latitude', 'longitude', 'address']);

        return view('veterinaire.dashboard', compact('results', 'searchQuery', 'allDistributors'));
    }

    // 3. إرسال طلب شراء للموزع
    public function storeOrder(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'receiver_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Commande::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'produit_id'  => $request->produit_id,
            'quantity'    => $request->quantity,
            'status'      => 'pending',
            'is_seen'     => false,
        ]);

        return back()->with('success', 'تم إرسال طلب الدواء للموزع بنجاح');
    }

    // 4. عرض سجل طلباتي وتصفير الإشعارات عند الدخول
    public function myOrders()
    {
        // تصفير الإشعارات عند دخول الصفحة
        Commande::where('sender_id', auth()->id())
                ->whereIn('status', ['accepted', 'rejected'])
                ->where('is_seen', false)
                ->update(['is_seen' => true]);

        $commandes = Commande::where('sender_id', auth()->id())
            ->with(['produit', 'receiver'])
            ->latest()
            ->get();
            
        return view('veterinaire.my_orders', compact('commandes'));
    }

    // 5. اقتراحات البحث (Ajax) لخاصية Autocomplete
    public function getSuggestions(Request $request)
    {
        $term = $request->q; // استخدام q كما في كود الـ JavaScript
        $suggestions = Produit::where('nom', 'LIKE', '%'.$term.'%')
                            ->select('nom')
                            ->distinct()
                            ->limit(10)
                            ->get();

        return response()->json($suggestions);
    }

    // 6. التبليغ عن الأوبئة
    public function report() { return view('veterinaire.report'); }

    public function sendReport(Request $request)
    {
        $request->validate([
            'disease_name' => 'required|string',
            'description'  => 'required',
            'location'     => 'required'
        ]);

        // كود الحفظ (اختياري حسب مشروعك)
        return redirect()->route('veterinaire.dashboard')->with('success', 'تم التبليغ عن الوباء بنجاح');
    }

    // 7. البروفايل والشات
    public function profile() { return view('veterinaire.profile', ['user' => auth()->user()]); }
    public function chats() { return view('veterinaire.chats'); }
}