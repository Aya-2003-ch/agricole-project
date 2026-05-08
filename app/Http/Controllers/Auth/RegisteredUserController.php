<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Distributeur;
use App\Models\Veterinaire;
use App\Models\Eleveur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1. التثبت من البيانات (Validation)
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:eleveur,veterinaire,distributeur,admin', // تأكدنا من وجود admin هنا
            'telephone' => 'required',
            'address' => 'required',
            
            // GPS 
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 2. إنشاء المستخدم (User)
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->telephone = $request->telephone;
        $user->address = $request->address;
        $user->role = $request->role;

        // حفظ الموقع
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;

        $user->save();

        // 3. إنشاء السجل في الجدول التابع للرول (إلا الأدمن لا يحتاج جدول إضافي)
        if ($request->role === 'distributeur') {
            Distributeur::create([
                'user_id' => $user->id,
                'nom' => $request->name,
                'telephone' => $request->telephone,
                'address' => $request->address,
            ]);
        }

        if ($request->role === 'veterinaire') {
            Veterinaire::create([
                'user_id' => $user->id,
                'nom' => $request->name,
                'telephone' => $request->telephone,
                'address' => $request->address,
                'specialite' => 'general',
            ]);
        }

        if ($request->role === 'eleveur') {
            Eleveur::create([
                'user_id' => $user->id,
                'nom' => $request->name,
                'telephone' => $request->telephone,
                'address' => $request->address,
            ]);
        } 
        
        // 4. تسجيل الدخول تلقائياً
        Auth::login($user);

        // 5. التوجيه الذكي حسب الـ Role (التعديل هنا)
        return match ($user->role) {
            'admin'        => redirect()->route('admin.panel'), // يذهب لجدول الأدمن الأخضر
            'veterinaire'  => redirect()->route('veterinaire.dashboard'),
            'distributeur' => redirect()->route('distributeur.dashboard'),
            'eleveur'      => redirect()->route('eleveur.dashboard'),
            default        => redirect('/'),
        };
    }
}