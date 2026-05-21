<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributeur;
use App\Models\Store;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;

class DistributeurController extends Controller
{
    public function dashboard()
{
    // 1. جلب بيانات الموزع الحالي
    $distributeur = Distributeur::where('user_id', auth()->id())->first();
    
    $totalProduits = 0;
    $incomingOrdersCount = 0; // إجمالي الطلبات المنتظرة (للمربعات الإحصائية)
    $unreadOrdersCount = 0;   // الطلبات الجديدة التي لم تُشاهد بعد (للرقم الأحمر)

    if ($distributeur) {
        // حساب إجمالي أنواع المنتجات في مخزن هذا الموزع
        $totalProduits = Store::where('distributeur_id', $distributeur->id)->count();

        // حساب جميع الطلبات التي حالتها "قيد الانتظار"
        $incomingOrdersCount = Commande::where('receiver_id', auth()->id())
                                        ->where('status', 'pending')
                                        ->count();

        // حساب الطلبات الجديدة التي لم يفتحها الموزع بعد (is_seen = false)
        $unreadOrdersCount = Commande::where('receiver_id', auth()->id())
                                      ->where('is_seen', false)
                                      ->count();
    }
    
    // 3. جلب جميع الموزعين للخريطة
    $allDistributors = Distributeur::all()->map(function($dist) {
        return [
            'nom'       => $dist->nom,
            'latitude'  => $dist->latitude,
            'longitude' => $dist->longitude,
            'address'   => $dist->address,
        ];
    });

    return view('distributeur.dashboard', compact(
        'totalProduits', 
        'allDistributors', 
        'incomingOrdersCount', 
        'unreadOrdersCount' 
    ));
}

    
public function market(Request $request) {
    $query = $request->input('query');

    // 1. يجب التأكد من وجود موزع مسجل للمستخدم الحالي لتفادي خطأ في الـ ID
    $distributeurId = auth()->user()->distributeur->id;

    // 2. نجلب المنتجات مع بيانات الموزع والمنتج (Eager Loading)
    $results = Store::with(['produit', 'distributeur']) 
        ->whereHas('produit', function($q) use ($query) {
            if ($query) {
                $q->where('nom', 'LIKE', "%{$query}%");
            }
        })
        ->where('distributeur_id', '!=', $distributeurId) // لا يظهر منتجاته الخاصة
        ->get();

    return view('distributeur.market', compact('results'));
}
    // عرض الطلبات الواردة
  public function incomingOrders()
{
    // 1. تحديث كافة الطلبات غير المقروءة لهذا الموزع لتصبح "مقروءة" بمجرد دخول الصفحة
    \App\Models\Commande::where('receiver_id', auth()->id())
        ->where('is_seen', false)
        ->update(['is_seen' => true]);

    // 2. جلب الطلبات لعرضها في الجدول
    $orders = \App\Models\Commande::where('receiver_id', auth()->id())
                ->whereHas('produit') 
                ->with(['produit', 'sender'])
                ->latest()
                ->get();

    return view('distributeur.incoming_orders', compact('orders'));
}

    // حفظ طلب شراء جديد
    public function storeOrder(Request $request)
{
    // 1. التحقق من البيانات
    $request->validate([
        'product_id'  => 'required|exists:produits,id',
        'receiver_id' => 'required|exists:users,id',
        'quantity'    => 'required|integer|min:1',
        'phone'       => 'required',
        'address'     => 'required',
    ]);

    // 2. إنشاء الطلب في قاعدة البيانات
    \App\Models\Commande::create([
        'sender_id'   => auth()->id(), // أنت المشتري
        'receiver_id' => $request->receiver_id,
        'product_id'  => $request->product_id,
        'quantity'    => $request->quantity,
        'phone'       => $request->phone,
        'address'     => $request->address,
        'status'      => 'pending', // الطلب يبدأ بحالة انتظار
    ]);

    // 3. العودة مع رسالة نجاح
    return redirect()->route('distributeur.dashboard')->with('success', 'تم إرسال طلب الشراء بنجاح ✅');
}
    // --- الدوال الأساسية ---

    public function profile()
    {
        return view('distributeur.profile', ['user' => auth()->user()]);
    }

    // هذه الدالة بقيت store لأنها مسؤولة عن "حفظ" بيانات الموزع (POST)
    public function store(Request $request)
    {
        Distributeur::create([
            'user_id' => auth()->id(),
            'nom' => $request->nom,
            'tele' => $request->tele,
            'localisation' => $request->localisation,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'تم إنشاء الموزع بنجاح');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'تم تحديث بياناتك بنجاح ✅');
    }
    // app/Http/Controllers/DistributeurController.php

public function getProductSuggestions(Request $request)
{
    $term = $request->input('term');
    
    // جلب أسماء المنتجات فقط التي تحتوي على الحروف المكتوبة
    $suggestions = \App\Models\Produit::where('nom', 'LIKE', "%{$term}%")
        ->pluck('nom') // نأخذ العمود 'nom' فقط
        ->take(10);    // نكتفي بـ 10 اقتراحات لتسريع الاستجابة

    return response()->json($suggestions);
}
public function acceptOrder(Commande $order)
{
    // 1. التأكد من الصلاحية (أن المستخدم الحالي هو مستلم الطلب)
    if ($order->receiver_id !== auth()->id()) {
        abort(403);
    }

    // 2. جلب بيانات الموزع المرتبطة بالمستخدم الحالي
    // لأن جدول Store يحتاج distributeur_id وليس user_id
    $distributeur = \App\Models\Distributeur::where('user_id', auth()->id())->first();

    if (!$distributeur) {
        return back()->with('error', 'لم يتم العثور على ملف موزع لهذا الحساب.');
    }

    // 3. البحث عن المخزون الخاص بهذا الموزع وهذا المنتج
    $inventory = \App\Models\Store::where('produit_id', $order->product_id)
                                  ->where('distributeur_id', $distributeur->id) // استخدام ID الموزع هنا
                                  ->first();

    if (!$inventory) {
        return back()->with('error', 'هذا المنتج غير موجود في مخزنك.');
    }

    // 4. التحقق من الكمية
    $requested = (int) $order->quantity;
    $available = (int) $inventory->quantite;

    if ($available < $requested) {
        return back()->with('error', "مخزونك لا يكفي. المتوفر: $available");
    }

    try {
        \DB::transaction(function () use ($order, $inventory, $requested) {
            
            // 5. نقص الكمية من المتجر
            $inventory->decrement('quantite', $requested);

            // 6. تحديث حالة الطلب
            $order->update([
                'status' => 'accepted',
                'is_seen' => false 
            ]);
        });

        return back()->with('success', 'تم قبول الطلب وتحديث المخزن بنجاح.');

    } catch (\Exception $e) {
        return back()->with('error', 'حدث خطأ تقني: ' . $e->getMessage());
    }
}
public function rejectOrder(Commande $order)
{
    // التأكد أن الموزع الحالي هو الذي استلم الطلب (البائع)
    if ($order->receiver_id !== auth()->id()) {
        abort(403);
    }

    $order->update([
        'status' => 'rejected',
        'is_seen' => false // نجعلها false لكي يظهر الإشعار للمشتري
    ]);

    return back()->with('error', 'تم رفض الطلب.');
}
  public function myOrders()
{
     Commande::where('sender_id', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);
    $orders = Commande::where('sender_id', auth()->id())
                ->with(['produit', 'receiver']) // جلب المنتج والموزع الذي استلم الطلب
                ->latest()
                ->paginate(10); // تقسيم الصفحة إذا كانت الطلبات كثيرة

    return view('distributeur.my_orders', compact('orders'));
}
  public function editOrder($id)
    {
        $order = Commande::findOrFail($id);
        return view('distributeur.edit_order', compact('order'));
    }
    public function destroyOrder($id)
    {
        $order = Commande::findOrFail($id);

        try {
            $order->delete();
            return redirect()->back()->with('success', 'تم حذف الطلب بنجاح من النظام.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء محاولة الحذف.');
        }
}
}