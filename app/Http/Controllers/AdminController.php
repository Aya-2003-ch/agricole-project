<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // نجلب كل المستخدمين لعرضهم للأدمن
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function delete($id)
    {
        // حذف المستخدم
        User::destroy($id);
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}