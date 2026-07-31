<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * GuardianApiController
 * 
 * Ce contrôleur gère l'authentification des parents via API REST.
 * Il fournit les endpoints pour la connexion, déconnexion et modification
 * du mot de passe des parents pour l'application mobile.
 */
class GuardianApiController extends Controller
{
    /**
     * Connexion d'un parent via API
     * 
     * POST /api/guardian/login
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion
        if (Auth::guard('guardian')->attempt($request->only('email', 'password'))) {
            $guardian = Auth::guard('guardian')->user();
            
            // Régénération de la session
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'guardian' => [
                        'id' => $guardian->id,
                        'first_name' => $guardian->first_name,
                        'last_name' => $guardian->last_name,
                        'email' => $guardian->email,
                        'phone' => $guardian->phone,
                    ],
                ],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Identifiants incorrects',
        ], 401);
    }

    /**
     * Déconnexion d'un parent via API
     * 
     * POST /api/guardian/logout
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('guardian')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ], 200);
    }

    /**
     * Modification du mot de passe d'un parent via API
     * 
     * POST /api/guardian/change-password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $guardian = $request->user();

        // Vérification du mot de passe actuel
        if (!Hash::check($request->current_password, $guardian->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect',
            ], 400);
        }

        // Mise à jour du mot de passe
        $guardian->password = Hash::make($request->new_password);
        $guardian->save();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès',
        ], 200);
    }

    /**
     * Récupérer les informations du parent connecté
     * 
     * GET /api/guardian/profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $guardian = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $guardian->id,
                'first_name' => $guardian->first_name,
                'last_name' => $guardian->last_name,
                'email' => $guardian->email,
                'phone' => $guardian->phone,
                'children_count' => $guardian->students()->count(),
            ],
        ], 200);
    }
}
