<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UtilisateurController extends Controller
{
    public function index()
    {
        return view('admin.utilisateurs.index', [
            'utilisateurs' => Admin::orderBy('nom')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.utilisateurs.form', ['utilisateur' => new Admin()]);
    }

    public function store(Request $request)
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:admins,email'],
            'role' => ['required', 'in:super_admin,editeur'],
            'mot_de_passe' => ['required', 'confirmed', Password::min(12)],
        ]);

        Admin::create($donnees);

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte créé.');
    }

    public function edit(Admin $utilisateur)
    {
        return view('admin.utilisateurs.form', ['utilisateur' => $utilisateur]);
    }

    public function update(Request $request, Admin $utilisateur)
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('admins', 'email')->ignore($utilisateur->id)],
            'role' => ['required', 'in:super_admin,editeur'],
            'mot_de_passe' => ['nullable', 'confirmed', Password::min(12)],
        ]);

        // Se retirer soi-même le rôle super_admin peut verrouiller définitivement l'accès.
        if ($utilisateur->is($request->user()) && $donnees['role'] !== 'super_admin') {
            return back()->withErrors(['role' => 'Vous ne pouvez pas retirer votre propre rôle super-administrateur.']);
        }

        if (blank($donnees['mot_de_passe'])) {
            unset($donnees['mot_de_passe']);
        }

        $utilisateur->update($donnees);

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte mis à jour.');
    }

    public function destroy(Request $request, Admin $utilisateur)
    {
        if ($utilisateur->is($request->user())) {
            return back()->withErrors(['nom' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        if ($utilisateur->estSuperAdmin() && Admin::where('role', 'super_admin')->count() <= 1) {
            return back()->withErrors(['nom' => 'Impossible de supprimer le dernier super-administrateur.']);
        }

        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')->with('succes', 'Compte supprimé.');
    }
}
