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
    $searchQuery = $request->input('medicine');

    $results = DB::table('produits')
        // 1. الربط مع جدول المخزن باستخدام produit_id
        ->join('stores', 'produits.id', '=', 'stores.produit_id')
        
        // 2. الربط مع جدول الموزعين (الوسيط) باستخدام distributeur_id
        ->join('distributeurs', 'stores.distributeur_id', '=', 'distributeurs.id')
        
        // 3. الربط مع جدول المستخدمين لجلب الاسم والعنوان والإحداثيات
        // ملاحظة: افترضي أن جدول distributeurs فيه حقل اسمه user_id يربطه بجدول users
        ->join('users', 'distributeurs.user_id', '=', 'users.id')
        
        ->select(
            'users.id as distributeur_id',
            'users.name as distributeur_name',
            'users.address as distributeur_address',
            'users.latitude as lat',
            'users.longitude as lng',
            'produits.id as produit_id',
            'produits.nom as medicine_name',
            'stores.prix as prix',
            'stores.quantite as stock'
        )
        ->where('produits.nom', 'LIKE', '%' . $searchQuery . '%')
        ->get();

    $allDistributors = User::where('role', 'distributeur')->get(['name', 'latitude', 'longitude', 'address']);

    return view('veterinaire.dashboard', compact('results', 'searchQuery', 'allDistributors'));
}
    // 3. إرسال طلب شراء للموزع
    public function storeOrder(Request $request)
{
    // 1. التحقق من البيانات (تأكد أن الاسم هنا يطابق الـ input في الـ Modal)
    $request->validate([
        'produit_id'  => 'required|exists:produits,id',
        'receiver_id' => 'required',
        'quantity'    => 'required|integer|min:1',
        'phone'       => 'required|string',
        'address'     => 'required|string',
    ]);

    // 2. الحفظ في قاعدة البيانات
    \App\Models\Commande::create([
        'sender_id'   => auth()->id(),
        'receiver_id' => $request->receiver_id,
        
        // التعديل هنا: نرسل القيمة للعمود الذي تطلبه قاعدة البيانات
        'product_id'  => $request->produit_id, 
        
        'quantity'    => $request->quantity,
        'phone'       => $request->phone,
        'address'     => $request->address,
        'status'      => 'pending',
    ]);

    return back()->with('success', 'تم إرسال طلبك بنجاح');
}

    // 4. عرض سجل طلباتي وتصفير الإشعارات عند الدخول
   public function myOrders()
{
    $userId = auth()->id();

    // 1. تحديث "تمت الرؤية" فقط للطلبات التي انتهى الموزع من معالجتها
    // ولا نقوم بتغيير الـ status هنا أبداً
    Commande::where('sender_id', $userId)
            ->whereIn('status', ['accepted', 'rejected']) 
            ->where('is_seen', false)
            ->update(['is_seen' => true]);

    // 2. جلب الطلبات مع التأكد من جلب العلاقات الصحيحة
    // ملاحظة: تأكدي هل اسم العلاقة 'produit' أم 'product' في الموديل
    $orders = Commande::where('sender_id', $userId)
                ->with(['produit', 'receiver']) 
                ->latest()
                ->get();

    return view('veterinaire.my_orders', compact('orders'));
}

    // 5. اقتراحات البحث (Ajax) لخاصية Autocomplete
  public function getSuggestions(Request $request)
{
    $query = $request->q;

    // استخدام الـ % قبل وبعد الكلمة لضمان إيجادها في أي مكان، 
    // أو إبقاء % في الأخير فقط حسب رغبتك
    $suggestions = Produit::where('nom', 'LIKE', '%' . $query . '%') 
                        ->distinct()
                        ->limit(10)
                        ->pluck('nom'); 

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