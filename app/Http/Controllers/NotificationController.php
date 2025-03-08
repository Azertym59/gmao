<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Afficher toutes les notifications
    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }
    
    // Marquer une notification comme lue
    public function markAsRead(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    // Marquer toutes les notifications comme lues
    public function markAllAsRead()
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
    
    // Supprimer une notification
    public function destroy(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur
        if ($notification->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Non autorisé');
        }
        
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification supprimée.');
    }
    
    // Obtenir les notifications non lues pour l'affichage dans la navbar
    public function getUnreadNotifications()
    {
        $notifications = auth()->user()->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $count = auth()->user()->notifications()->where('is_read', false)->count();
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }
}