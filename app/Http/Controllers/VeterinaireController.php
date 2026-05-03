<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Commande; 
use Illuminate\Http\Request;
use App\Models\Produit; 

class VeterinaireController extends Controller
{
    // 1. الداشبورد مع نظام التنبيهات (الشريط الأصفر)
    public function dashboard()
    {
        // جلب الاستشارات مع بيانات المستخدم (الفلاح)
        $consultations = Consultation::with('user')->latest()->get();
        
        // حساب عدد الطلبات الجديدة للتنبيهات باستعمال العمود الصحيح 'statut'
        $newOrdersCount = Commande::where('statut', 'pending')->count(); 

        return view('veterinaire.dashboard', compact('consultations', 'newOrdersCount'));
    }

    // 2. عرض قائمة الطلبات بالتفصيل للبيطري
    public function orders()
    {
        // جلب كل الطلبات القادمة مع بيانات الفلاح صاحب الطلب
        $commandes = Commande::with('user')->latest()->get();
        return view('veterinaire.orders', compact('commandes'));
    }

    // 3. صفحة الأدوية (البحث والترتيب حسب الموقع الجغرافي)
   public function medicines()
{
    // جلب كل الأدوية من قاعدة البيانات
    $medicines = Produit::all(); 
    
    return view('veterinaire.medicines', compact('medicines'));
}

    // 4. صفحة الاستشارات (تم إصلاح الخطأ بتمرير المتغير $consultations)
    public function consultations() 
    { 
        // جلب البيانات لكي لا يظهر خطأ Undefined variable
        $consultations = Consultation::with('user')->latest()->get();
        return view('veterinaire.consultations', compact('consultations')); 
    }
    
    // 5. صفحة البروفايل الشخصي
    public function profile() 
    { 
        return view('veterinaire.profile', ['user' => auth()->user()]); 
    }

    // 6. صفحة المحادثات بين البيطري والفلاح
    public function chats() 
    { 
        return view('veterinaire.chats'); 
    }

    // 7. دالة تحديث حالة الطلب (استعمال 'statut' ليتوافق مع قاعدة البيانات)
    public function updateStatus(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        
        // التأكد من تحديث الحقل الصحيح 'statut'
        $commande->update(['statut' => $request->statut]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
    // لعرض صفحة إضافة دواء جديد
public function createProduit()
{
    return view('veterinaire.create_produit');
}

// لعرض صفحة تعديل دواء موجود
public function editProduit($id)
{
    $produit = Produit::findOrFail($id);
    return view('veterinaire.edit_produit', compact('produit'));
}
}