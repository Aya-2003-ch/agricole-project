<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Distributeur;
use App\Models\Veterinaire;
use App\Models\Eleveur;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // ✅ VALIDATION (مهم)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // 🔥 role محدد فقط
            'role' => ['required', 'in:distributeur,veterinaire,eleveur'],

            // ✅ الجدد
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        // ✅ CREATE USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // 🔥 AUTO CREATION حسب role
        switch ($user->role) {

            case 'distributeur':
                Distributeur::create([
                    'user_id' => $user->id,
                ]);
                break;

            case 'veterinaire':
                Veterinaire::create([
                    'user_id' => $user->id,
                ]);
                break;

            case 'eleveur':
                Eleveur::create([
                    'user_id' => $user->id,
                ]);
                break;
        }

        event(new Registered($user));

        Auth::login($user);

        // 💬 message
        $message = 'مرحبا ' . $user->name . ' 👋';

        return redirect('/dashboard')->with('success', $message);
    }
}