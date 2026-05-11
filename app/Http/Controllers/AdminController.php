<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Commande;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // 1. جلب المستخدمين للجدول
        $users = User::all();

        // نربط product_id من جدول الطلبات مع produit_id من جدول الستور
        $totalRevenue = DB::table('commandes')
            ->join('stores', 'commandes.product_id', '=', 'stores.produit_id') 
            ->where('commandes.status', 'delivered')
            ->sum(DB::raw('commandes.quantity * stores.prix'));

        // 3. عدد الطلبات الكلي
        $ordersCount = Commande::count();

        // 4. عدد الاستشارات النشطة
        $activeConsultations = Consultation::where('status', 'pending')->count();

        return view('admin.index', compact('users', 'totalRevenue', 'ordersCount', 'activeConsultations'));
    }

    public function delete($id)
    {
        if ($id !== auth()->id()) {
            User::destroy($id);
            return back()->with('success', 'تم حذف المستخدم بنجاح');
        }
        return back()->with('error', 'لا يمكنك حذف حسابك الحالي');
    }
}