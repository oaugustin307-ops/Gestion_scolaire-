<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

/**
 * NotificationController
 * 
 * Ce contrôleur gère les opérations CRUD pour les notifications/annonces.
 * Il permet aux gestionnaires de créer, modifier, supprimer et lister les notifications.
 */
class NotificationController extends Controller
{
    /**
     * Afficher la liste de toutes les notifications
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $notifications = Notification::latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Afficher le formulaire de création d'une notification
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('notifications.create');
    }

    /**
     * Enregistrer une nouvelle notification
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:meeting,exam,payment,general,urgent',
            'priority' => 'required|in:low,medium,high',
            'date' => 'nullable|date',
        ]);

        Notification::create($validated);

        return redirect()->route('notifications.index')
            ->with('success', 'Notification créée avec succès.');
    }

    /**
     * Afficher les détails d'une notification
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return view('notifications.show', compact('notification'));
    }

    /**
     * Afficher le formulaire de modification d'une notification
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        return view('notifications.edit', compact('notification'));
    }

    /**
     * Mettre à jour une notification
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:meeting,exam,payment,general,urgent',
            'priority' => 'required|in:low,medium,high',
            'date' => 'nullable|date',
        ]);

        $notification = Notification::findOrFail($id);
        $notification->update($validated);

        return redirect()->route('notifications.index')
            ->with('success', 'Notification modifiée avec succès.');
    }

    /**
     * Supprimer une notification
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notification supprimée avec succès.');
    }

    /**
     * Marquer une notification comme lue
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return redirect()->back()
            ->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer une notification comme non lue
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsUnread($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = false;
        $notification->save();

        return redirect()->back()
            ->with('success', 'Notification marquée comme non lue.');
    }
}
