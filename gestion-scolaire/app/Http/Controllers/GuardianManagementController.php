<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * GuardianManagementController
 * 
 * Ce contrôleur gère les opérations CRUD pour les parents/tuteurs.
 * Il permet aux administrateurs d'ajouter, modifier, supprimer et lister les parents.
 */
class GuardianManagementController extends Controller
{
    /**
     * Afficher la liste de tous les parents
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $guardians = Guardian::with('students')->latest()->get();
        return view('guardians.index', compact('guardians'));
    }

    /**
     * Afficher le formulaire de création d'un parent
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('guardians.create');
    }

    /**
     * Enregistrer un nouveau parent
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:parents,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        Guardian::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('guardians.index')
            ->with('success', 'Parent ajouté avec succès.');
    }

    /**
     * Afficher les détails d'un parent
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $guardian = Guardian::with('students.class')->findOrFail($id);
        return view('guardians.show', compact('guardian'));
    }

    /**
     * Afficher le formulaire de modification d'un parent
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $guardian = Guardian::findOrFail($id);
        return view('guardians.edit', compact('guardian'));
    }

    /**
     * Mettre à jour un parent
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:parents,email,' . $id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $guardian = Guardian::findOrFail($id);
        
        $guardian->first_name = $request->first_name;
        $guardian->last_name = $request->last_name;
        $guardian->email = $request->email;
        $guardian->phone = $request->phone;
        
        if ($request->filled('password')) {
            $guardian->password = Hash::make($request->password);
        }
        
        $guardian->save();

        return redirect()->route('guardians.index')
            ->with('success', 'Parent modifié avec succès.');
    }

    /**
     * Supprimer un parent
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $guardian = Guardian::findOrFail($id);
        
        // Vérifier si le parent a des enfants
        if ($guardian->students()->count() > 0) {
            return redirect()->route('guardians.index')
                ->with('error', 'Impossible de supprimer ce parent car il a des enfants associés.');
        }
        
        $guardian->delete();

        return redirect()->route('guardians.index')
            ->with('success', 'Parent supprimé avec succès.');
    }
}
