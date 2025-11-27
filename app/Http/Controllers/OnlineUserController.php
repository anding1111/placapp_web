<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OnlineUserController extends Controller
{
    /**
     * Muestra la vista de usuarios en línea
     */
    public function index()
    {
        return view('users.online');
    }
    
    /**
     * Actualiza el estado en línea del usuario actual
     */
    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            $user->online_status = 1;
            $user->last_connection = now();
            $user->save();
            
            // Limpiar usuarios inactivos (más de 1 minuto)
            User::where('online_status', 1)
                ->where('last_connection', '<', now()->subMinutes(1))
                ->update(['online_status' => 0]);
                
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Usuario no autenticado']);
    }
    
    /**
     * Obtiene la lista de usuarios en línea
     */
    public function fetchOnlineUsers()
    {
        // Limpiar usuarios inactivos primero
        User::where('online_status', 1)
            ->where('last_connection', '<', now()->subSeconds(5))
            ->update(['online_status' => 0]);
        
        // Obtener usuarios activos
        $users = User::where('online_status', 1)
                    ->where('user_status', '>', 0)
                    ->select('id as id', 'username as user', 'name', 'role')
                    ->get();
        
        return response()->json($users);
    }
}