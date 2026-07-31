<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

/**
 * NotificationApiController
 * 
 * Ce contrôleur gère les notifications et annonces via API REST.
 * Il fournit les endpoints pour les annonces de l'école et les notifications importantes.
 */
class NotificationApiController extends Controller
{
    /**
     * Récupérer toutes les notifications pour un parent
     * 
     * GET /api/guardian/notifications
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        $notifications = Notification::latest()->get();
        
        $notificationsData = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'date' => $notification->date ? $notification->date->format('Y-m-d') : null,
                'priority' => $notification->priority,
                'is_read' => $notification->is_read,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $notificationsData,
        ], 200);
    }

    /**
     * Récupérer les notifications non lues
     * 
     * GET /api/guardian/notifications/unread
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadNotifications(Request $request)
    {
        $unreadNotifications = Notification::where('is_read', false)->latest()->get();
        
        $notificationsData = $unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'date' => $notification->date ? $notification->date->format('Y-m-d') : null,
                'priority' => $notification->priority,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $notificationsData,
            'count' => count($notificationsData),
        ], 200);
    }

    /**
     * Marquer une notification comme lue
     * 
     * POST /api/guardian/notifications/{id}/read
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification non trouvée',
            ], 404);
        }
        
        $notification->is_read = true;
        $notification->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue',
        ], 200);
    }
}
