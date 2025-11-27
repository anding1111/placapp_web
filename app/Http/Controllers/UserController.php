<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // El usuario actual solo puede crear usuarios de nivel igual o inferior
        $authUser = Auth::user();
        $availableLevels = $this->getAvailableLevels($authUser->level);
        
        return view('users.create', compact('availableLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:1,2',
            'level' => [
                'required',
                'integer',
                'min:0',
                'max:3',
                function ($attribute, $value, $fail) use ($authUser) {
                    // Verificar que el nivel sea igual o inferior al del usuario autenticado
                    if ($value < $authUser->level) {
                        $fail('No puedes crear un usuario con nivel superior al tuyo.');
                    }
                },
            ],
        ]);
        
        $user = new User();
        $user->username = $validated['username'];
        $user->password = Hash::make($validated['password']);
        $user->name = $validated['name'];
        $user->role = $validated['rol'];
        $user->level = $validated['level'];
        $user->user_status = 1;
        $user->online_status = 0;
        $user->last_connection = now();
        $user->save();
        
        return redirect()->route('users.index')->with('success', 'Usuario agregado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $authUser = Auth::user();
        $user = User::findOrFail($id);
        
        // Verificar que el usuario actual tenga permiso para editar este usuario
        if (!$this->canManageUser($authUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'No tienes permisos para editar este usuario');
        }
        
        $availableLevels = $this->getAvailableLevels($authUser->level);
        
        return view('users.edit', compact('user', 'availableLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $authUser = Auth::user();
        $user = User::findOrFail($id);
        
        // Verificar que el usuario actual tenga permiso para editar este usuario
        if (!$this->canManageUser($authUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'No tienes permisos para editar este usuario');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'level' => [
                'required',
                'integer',
                'min:0',
                'max:3',
                function ($attribute, $value, $fail) use ($authUser) {
                    // Verificar que el nivel sea igual o inferior al del usuario autenticado
                    if ($value < $authUser->level) {
                        $fail('No puedes asignar un nivel superior al tuyo.');
                    }
                },
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        
        $user->name = $validated['name'];
        $user->level = $validated['level'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * API para DataTables - Obtener usuarios
     */
    public function getUsersData(Request $request)
    {
        $authUser = Auth::user();
        $level_user = $authUser->level;
        
        // El usuario solo puede ver usuarios de su mismo nivel o inferior
        $users = User::where('user_status', 1)
                    ->where('role', '>=', 1)
                    ->where('level', '>=', $level_user);
        
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('role', function ($user) {
                return $user->role == 1 ? 'Administrador' : 'Usuario';
            })
            ->addColumn('action', function ($user) use ($authUser) {
                $actions = '';
                
                // Solo mostrar botón de editar si tiene permisos
                if ($this->canManageUser($authUser, $user)) {
                    $actions .= '<a href="' . route('users.edit', $user->id) . '" class="btn btn-secondary btn-small">Editar</a>';
                    $actions .= '<a href="#null_modal_user" class="invoiceInfoUser btn btn-light btn-small" id="invId" data-toggle="modal" data-id="' . $user->id . '">Borrar</a>';
                }
                
                return $actions;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    
    /**
     * Obtener información de un usuario específico para el modal
     */
    public function getUserDetails(Request $request)
    {
        $userId = $request->input('userId');
        $authUser = Auth::user();
        
        $user = User::where('id', $userId)
                   ->where('user_status', 1)
                   ->first();
                   
        if (!$user || !$this->canManageUser($authUser, $user)) {
            return '<p>No se encontró el usuario o no tienes permisos para verlo</p>';
        }
        
        // Construir HTML para el modal
        $html = '<div class="row" style="font-size:12px; margin-left:10px;">';
        $html .= '<div class="col-sm-6 invoice-col">';
        $html .= '<input type="hidden" id="numInvoice" value="'.$userId.'">';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-sm-12" style="margin-top: 15px;">';
        $html .= '<table class="table table-hover" border="0" width="100%">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="width:40%">Nombre</th>';
        $html .= '<th style="width:35%; text-align:right">Usuario</th>';
        $html .= '<th style="width:25%; text-align:right">Perfil</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody style="max-height: 30vh; overflow-y: auto; overflow-x: hidden;">';
        
        $html .= '<tr>';
        $html .= '<td style="width:40%">'.$user->name.'</td>';
        $html .= '<td style="text-align:right; width:35%">'.$user->username.'</td>';
        $html .= '<td style="text-align:right; width:25%">'.($user->role == 1 ? 'Administrador' : 'Usuario').'</td>';
        $html .= '</tr>';
        
        $html .= '</tbody></table></div>';
        
        return $html;
    }
    
    /**
     * Elimina un usuario (cambiar estado a inactivo)
     */
    public function nullUser(Request $request)
    {
        $userId = $request->input('invId');
        $authUser = Auth::user();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'ID de usuario no proporcionado']);
        }
        
        $user = User::find($userId);
        
        if (!$user || !$this->canManageUser($authUser, $user)) {
            return response()->json(['success' => false, 'message' => 'No se encontró el usuario o no tienes permisos para eliminarlo']);
        }
        
        $user->user_status = 0;
        $user->save();
                
        return response()->json(['success' => true]);
    }
    
    /**
     * Verifica si el usuario autenticado puede gestionar (ver/editar/eliminar) a otro usuario
     */
    private function canManageUser($authUser, $targetUser)
    {
        // Solo puede gestionar usuarios de nivel igual o inferior
        return $authUser->level <= $targetUser->level;
    }
    
    /**
     * Obtiene los niveles disponibles para un usuario según su nivel
     */
    private function getAvailableLevels($userLevel)
    {
        $levels = [];
        
        // Añadir el nivel 0 solo si el usuario es nivel 0
        if ($userLevel == 0) {
            $levels[0] = '0';
        }
        
        // Añadir los niveles del 1 al 3 si son iguales o inferiores al nivel del usuario
        for ($i = 1; $i <= 3; $i++) {
            if ($i >= $userLevel) {
                $levels[$i] = (string)$i;
            }
        }
        
        return $levels;
    }
}