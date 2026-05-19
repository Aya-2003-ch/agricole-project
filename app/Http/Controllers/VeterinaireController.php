<?php

namespace App\Http\Controllers; 

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Consultation;
use App\Models\Commande; 
use App\Models\Produit; 
use App\Models\User;
use Illuminate\Http\Request;

class VeterinaireController extends Controller
{
    // 1. الداشبورد: عرض الاستشارات، التنبيهات، والخريطة
    public function dashboard()
    {
        // استشارات الفلاحين الموجهة لهذا البيطري مع جلب علاقة الفلاح والحيوان لضمان دقة البيانات
        $consultations = Consultation::where('veterinaire_id', auth()->id())
            ->with(['user', 'animal']) 
            ->latest()
            ->get(); // نزعنا take(5) لكي تظهر كافة الطلبات، يمكنك إعادتها إن أردت
        
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
        // 1. التحقق من البيانات
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
        Commande::where('sender_id', $userId)
                ->whereIn('status', ['accepted', 'rejected']) 
                ->where('is_seen', false)
                ->update(['is_seen' => true]);

        // 2. جلب الطلبات مع العلاقات الصحيحة
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

        $suggestions = Produit::where('nom', 'LIKE', '%' . $query . '%') 
                            ->distinct()
                            ->limit(10)
                            ->pluck('nom'); 

        return response()->json($suggestions);
    }

    // ==========================================
    // الدوال الجديدة والمعدلة لإدارة الاستشارات وحل مشكلة الـ groupBy
    // ==========================================

    // 6. دالة اقتراح موعد من طرف الطبيب وتحديث كافة الأسطر المجمعة
    public function proposeAppointment(Request $request, $id)
    {
        $request->validate([
            'date_consultation' => 'required|date',
        ]);

        // جلب سطر الاستشارة الحالي لمعرفة تفاصيل المجموعة
        $consultation = Consultation::where('veterinaire_id', auth()->id())->findOrFail($id);

        // تحديث جميع الحيوانات المشتركة في نفس طلب الفلاح (نفس التاريخ والسبب)
        Consultation::where('eleveur_id', $consultation->eleveur_id)
            ->where('date_demande', $consultation->date_demande)
            ->where('motif', $consultation->motif)
            ->update([
                'status' => 'accepted', // الحالة التي يبحث عنها كود الفلاح لإظهار أزرار القبول والرفض
                'date_consultation' => $request->date_consultation
            ]);

        return back()->with('success', 'تم تحديد الموعد وإرساله إلى الفلاح بنجاح!');
    }

    // 7. دالة رفض طلب الاستشارة بالكامل
    public function rejectConsultation($id)
    {
        $consultation = Consultation::where('veterinaire_id', auth()->id())->findOrFail($id);

        // رفض المجموعة كاملة
        Consultation::where('eleveur_id', $consultation->eleveur_id)
            ->where('date_demande', $consultation->date_demande)
            ->where('motif', $consultation->motif)
            ->update([
                'status' => 'declined'
            ]);

        return back()->with('success', 'تم رفض طلب الاستشارة بنجاح.');
    }

    // 8. البروفايل والشات
    public function profile() { return view('veterinaire.profile', ['user' => auth()->user()]); }
    public function chats() { return view('veterinaire.chats'); }
    // دالة حفظ التشخيص والعلاج للاستشارة الطبية لجميع الحيوانات المجمعة
// دالة حفظ التشخيص والعلاج للاستشارة الطبية لجميع الحيوانات المجمعة
public function storeDiagnosis(Request $request, $id)
{
    // 1. التحقق من وجود مصفوفة البيانات القادمة
    $request->validate([
        'data' => 'required|array',
        'data.*.diagnostique' => 'required|string',
        'data.*.traitement' => 'required|string',
    ]);

    // 2. الدوران حول كل حيوان في الطلب وحفظ بياناته بناءً على الـ ID الخاص به
    foreach ($request->data as $consultationId => $values) {
        \App\Models\Consultation::where('id', $consultationId)
            ->where('veterinaire_id', auth()->id())
            ->update([
                'diagnostique' => $values['diagnostique'], // الاسم الجديد المطابق للداتابيز
                'traitement'   => $values['traitement'],
                'status'       => 'accepted' // الحفاظ على الحالة مقبولة ومؤكدة عند الطرفين
            ]);
    }

    return back()->with('success', 'تم حفظ التشخيص والملف الطبي لجميع الحيوانات بنجاح!');
}
}