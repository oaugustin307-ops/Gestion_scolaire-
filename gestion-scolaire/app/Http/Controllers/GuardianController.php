<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * GuardianController
 * 
 * Ce contrôleur gère l'authentification des parents/tuteurs.
 * Il permet aux parents de se connecter pour accéder aux informations
 * de leurs enfants.
 * 
 * Fonctionnalités principales :
 * - Affichage du formulaire de connexion pour les parents
 * - Traitement de la connexion des parents
 * - Déconnexion des parents
 */
class GuardianController extends Controller
{
    /**
     * Affiche le formulaire de connexion pour les parents
     * 
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('guardians.login');
    }

    /**
     * Traite la tentative de connexion d'un parent
     * 
     * Cette méthode valide les identifiants du parent et tente
     * de le connecter en utilisant le guard 'guardian'.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validation des données du formulaire
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion avec le guard 'guardian'
        if (Auth::guard('guardian')->attempt($credentials)) {
            // Régénération de la session pour éviter les attaques de fixation
            $request->session()->regenerate();
            
            return redirect()->intended('/guardian/dashboard')
                ->with('success', 'Bienvenue ' . Auth::guard('guardian')->user()->first_name);
        }

        // Échec de la connexion
        return back()->with('error', 'Les identifiants fournis ne correspondent pas.');
    }

    /**
     * Déconnecte le parent connecté
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('guardian')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('guardian.login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Affiche le tableau de bord du parent
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $guardian = Auth::guard('guardian')->user();
        $students = $guardian->students()->with('class', 'grades')->get();
        
        return view('guardians.dashboard', compact('guardian', 'students'));
    }
}
