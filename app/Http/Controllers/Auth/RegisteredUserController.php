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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
            'telephone' => 'required',
            'address' => 'required',
        ]);

        // 1️⃣ Create User
          $user = new User();

         $user->name = $request->name;
         $user->email = $request->email;
         $user->password = Hash::make($request->password);
         $user->telephone = $request->telephone;
         $user->address = $request->address;
         $user->role = $request->role;

         $user->save(); // 👈 أهم سطر

        // 2️⃣ Role tables
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
                'ferme' => $request->address,
            ]);
        }

        // 3️⃣ Login
        Auth::login($user);

        // 4️⃣ Redirect
        return match ($user->role) {
            'veterinaire' => redirect()->route('veterinaire.dashboard'),
            'distributeur' => redirect()->route('distributeur.dashboard'),
            'eleveur' => redirect()->route('eleveur.dashboard'),
            default => redirect('/'),
        };
    }
}