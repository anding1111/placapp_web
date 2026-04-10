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
     * Actualiza el estado en línea del usuario actual con optimización de base de datos
     */
    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            // Optimización: Solo guardar en DB si han pasado más de 15 segundos 
            // Esto reduce la carga de escritura en un 80% si el polling es de 3s
            $shouldUpdate = !$user->last_connection || $user->last_connection->diffInSeconds(now()) > 15;
            
            if ($shouldUpdate || $user->online_status == 0) {
                $user->online_status = 1;
                $user->last_connection = now();
                $user->save();
            }
            
            // Limpieza probabilística (Garbage Collection): Solo 1 de cada 10 peticiones 
            // realiza la limpieza costosa de otros usuarios inactivos
            if (rand(1, 10) === 1) {
                User::where('online_status', 1)
                    ->where('last_connection', '<', now()->subMinutes(2))
                    ->update(['online_status' => 0]);
            }
                
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Usuario no autenticado']);
    }
    
    /**
     * Obtiene la lista de usuarios en línea optimizada
     */
    public function fetchOnlineUsers()
    {
        // En la vista de lista, somos un poco más agresivos con la limpieza para mantenerla real
        User::where('online_status', 1)
            ->where('last_connection', '<', now()->subSeconds(10))
            ->update(['online_status' => 0]);
        
        // Obtener usuarios activos con selección de campos mínima
        $users = User::where('online_status', 1)
                    ->where('user_status', '>', 0)
                    ->select('id', 'username as user', 'name', 'role')
                    ->get();
        
        return response()->json($users);
    }
}