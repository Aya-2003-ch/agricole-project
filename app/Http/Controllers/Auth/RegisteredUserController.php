<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        //  validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'], //  role
        ]);

        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, 
        ]);

        event(new Registered($user));

        Auth::login($user);

// الروابط هادو جبتهم من ملف الـ web.php تاعك باش ما نغلطوش
if ($user->role == 'farmer') {
    return redirect('/ferme/dashboard'); 
}

if ($user->role == 'veterinaire') {
    return redirect('/veterinaire/dashboard'); 
}

if ($user->role == 'distributeur') {
    return redirect('/distributeur/dashboard');
} // هادا تاع الـ if الأخير

        return redirect('/dashboard');
    } 
    }