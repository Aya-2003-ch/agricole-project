<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\User;
use App\Models\Livreur;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    //  afficher
    public function index()
    {
        $commandes = Commande::with('user', 'livreur')->get();

        $users = User::all();
        $livreurs = Livreur::all();

        return view('commandes.index', compact('commandes', 'users', 'livreurs'));
    }

    //  ajouter
    public function store(Request $request)
    {
        $this->authorize('create', Commande::class);

        Commande::create([
            'user_id' => $request->user_id,
            'livreur_id' => $request->livreur_id,
            'date_commande' => $request->date_commande,
            'statut' => $request->statut,
        ]);

        return redirect()->back();
    }

    //  edit
    public function edit($id)
    {
        $commande = Commande::findOrFail($id);

        $this->authorize('update', $commande);

        $users = User::all();
        $livreurs = Livreur::all();

        return view('commandes.edit', compact('commande', 'users', 'livreurs'));
    }

    // update
    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        $this->authorize('update', $commande);

        $commande->update([
            'user_id' => $request->user_id,
            'livreur_id' => $request->livreur_id,
            'date_commande' => $request->date_commande,
            'statut' => $request->statut,
        ]);

        return redirect()->route('commandes.index');
    }

    //  delete
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);

        $this->authorize('delete', $commande);

        $commande->delete();

        return redirect()->back();
    }
}