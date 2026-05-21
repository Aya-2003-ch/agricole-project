<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // جلب دور المستخدم الحالي بعد تسجيل الدخول بنجاح
        $role = auth()->user()->role;

        // التوجيه الذكي حسب الأدوار الأربعة لمنصة AgroDz
        if ($role === 'admin') {
            return redirect()->route('admin.panel');
        } elseif ($role === 'eleveur') {
            return redirect()->route('eleveur.dashboard');
        } elseif ($role === 'veterinaire') {
            return redirect()->route('veterinaire.dashboard');
        } elseif ($role === 'distributeur') {
            return redirect()->route('distributeur.dashboard');
        }

        // التوجيه الافتراضي الاحتياطي
        return redirect('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
