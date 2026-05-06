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
        
        // 2. حساب إجمالي المنتجات في المخزن
        $totalProduits = 0;
        if ($distributeur) {
            $totalProduits = Store::where('distributeur_id', $distributeur->id)->count();
        }

        // 3. جلب جميع الموزعين للخريطة
        $allDistributors = Distributeur::with('user')->get()->map(function($dist) {
            return [
                'name' => $dist->nom,
                'lat'  => $dist->latitude,
                'lng'  => $dist->longitude,
                'address' => $dist->localisation,
            ];
        });

        return view('distributeur.dashboard', compact('totalProduits', 'allDistributors'));
    }

    // --- الميزات الجديدة (Marche et Commandes) ---

    // 1. تغيير اسم هذه الدالة من store إلى market لمنع التكرار مع دالة الحفظ
    // app/Http/Controllers/DistributeurController.php

public function market(Request $request) {
    $query = $request->input('query');

    // نجلب المنتجات التي يطابق اسمها البحث
    $results = Store::whereHas('produit', function($q) use ($query) {
        $q->where('nom', 'LIKE', "%{$query}%");
    })
    ->where('distributeur_id', '!=', auth()->user()->distributeur->id) // استثناء مخزنك الشخصي
    ->get();

    return view('distributeur.market', compact('results'));
}

    // عرض الطلبات الواردة
    public function incomingOrders()
    {
        // تم تغيير 'product' إلى 'produit' ليتوافق مع علاقات Store
        $orders = Commande::where('receiver_id', auth()->id())
                          ->where('order_type', 'to_distributeur')
                          ->with(['sender', 'produit']) 
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
        'telephone'   => $request->telephone,
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
}
