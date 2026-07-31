<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Debug: afficher les rôles reçus
        \Log::info('RoleMiddleware - Rôles reçus: ' . json_encode($roles));
        \Log::info('RoleMiddleware - Rôle utilisateur: ' . Auth::user()->role);

        // Si un seul paramètre est passé avec des virgules, l'exploder
        $allowedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, explode(',', $role));
            } else {
                $allowedRoles[] = $role;
            }
        }
        
        // Nettoyer les espaces
        $allowedRoles = array_map('trim', $allowedRoles);
        
        \Log::info('RoleMiddleware - Rôles autorisés: ' . json_encode($allowedRoles));
        
        // Vérifier si le rôle de l'utilisateur est dans la liste des rôles autorisés
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }

        return $next($request);
    }
}
