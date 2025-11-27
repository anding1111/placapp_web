<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $redirectTo = '/home';

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Intentar autenticación
        if (Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'user_status' => 1
        ], $request->filled('remember'))) {
            $user = Auth::user();
            $uuid = $request->input('uuid');

            // Comenta estas líneas para deshabilitar la validación de UUID

            $uuid_check = Uuid::where('user_id', $user->id)
                            ->where('uuid', $uuid)
                            ->where('status', 1)
                            ->first();

            if ($uuid_check) {
                // Dispositivo autorizado
                $user->online_status = 1;
                $user->last_connection = now();
                $user->save();
                
                $request->session()->regenerate();
                
                if ($request->wantsJson()) {
                    return response()->json(['success' => true, 'redirect' => $this->redirectTo]);
                }
                
                return redirect()->intended($this->redirectTo);
            } else {
                // Dispositivo no autorizado
                Auth::logout();
                
                // Guardar el UUID en sesión para el formulario de autorización
                $request->session()->put('pending_uuid', $uuid);
                $request->session()->put('pending_user_id', $user->id);
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'error' => 'Dispositivo no autorizado', 
                        'uuid' => $uuid,
                        'unauthorized' => true
                    ], 403);
                }

                return redirect()->back()
                    ->withInput($request->only('username'))
                    ->withErrors(['error' => 'Dispositivo no autorizado']);
            }
            // Si deseas autorizar automáticamente a todos los dispositivos, descomenta las siguientes líneas
            // y comenta la validación de UUID anterior.

            // Añade este código en su lugar para autorizar automáticamente a todos
            // $user->online_status = 1;
            // $user->last_connection = now();
            // $user->save();
            
            // $request->session()->regenerate();
            
            // if ($request->wantsJson()) {
            //     return response()->json(['success' => true, 'redirect' => $this->redirectTo]);
            // }
            
            // return redirect()->intended($this->redirectTo);

        }

        // Credenciales inválidas
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Usuario o Contraseña Incorrectos'], 401);
        }

        return redirect()->back()
            ->withInput($request->only('username'))
            ->withErrors(['error' => 'Usuario o Contraseña Incorrectos']);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->online_status = 0;
            $user->save();
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'uuid' => 'required|string|max:50', // Asegura que el UUID se envíe
        ]);
    }

    /**
     * Muestra la página de autorización de dispositivo.
     */
    public function showDeviceAuth(Request $request)
    {
        $uuid = $request->session()->get('pending_uuid');
        $userId = $request->session()->get('pending_user_id');
        
        if (!$uuid || !$userId) {
            return redirect()->route('login');
        }
        
        return view('auth.device_auth', [
            'uuid' => $uuid,
            'userId' => $userId
        ]);
    }

    /**
     * Autoriza un dispositivo para un usuario.
     */
    public function authorizeDevice(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string|max:50',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            Uuid::create([
                'user_id' => $request->input('user_id'),
                'uuid' => $request->input('uuid'),
                'status' => 1
            ]);

            $request->session()->forget(['pending_uuid', 'pending_user_id']);
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Dispositivo autorizado correctamente']);
            }
            
            return redirect()->route('login')->with('success', 'Dispositivo autorizado correctamente. Por favor, inicie sesión nuevamente.');
        } catch (\Exception $e) {
            Log::error('Error al autorizar dispositivo: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Error al autorizar el dispositivo'], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Error al autorizar el dispositivo. Por favor, intente nuevamente.']);
        }
    }

    /**
     * Genera un UUID para el dispositivo.
     */
    public static function generateDeviceId()
    {
        $os = php_uname('s') . ' ' . php_uname('r');
        $machine = php_uname('m') . '-' . substr(md5(php_uname('n')), 0, 8);
        $uniqueId = Str::uuid()->toString();
        
        return $os . '-' . $machine . '-' . substr($uniqueId, 0, 8);
    }
}