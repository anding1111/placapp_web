<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OnlineUserController extends Controller
{
    /**
     * Muestra la página de usuarios en línea
     */
    public function index()
    {
        return view('online.index');
    }
    
    /**
     * Actualiza el estado en línea del usuario actual
     */
    public function updateStatus(Request $request)
    {
        try {
            // Actualizar el estado del usuario actual
            $user = Auth::user();
            
            if ($user) {
                $status = $request->input('status', 1); // 1 = online, 0 = offline
                
                $user->online_status = $status;
                $user->last_connection = Carbon::now();
                $user->save();
            }
            
            // Limpiar usuarios inactivos (más de 3 minutos sin actualizar)
            User::where('online_status', 1)
                ->where('last_connection', '<', Carbon::now()->subMinutes(3))
                ->update(['online_status' => 0]);
                
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtiene la lista de usuarios en línea
     */
    public function fetchOnlineUsers()
    {
        try {
            $users = User::where('online_status', 1)
                        ->where('user_status', '>', 0)
                        ->select('user_id as id', 'username as user', 'name', 'role')
                        ->get();
                        
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }
}