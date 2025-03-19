<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Uuid;

class AuthController extends Controller
{
    protected $redirectTo = '/home';

    // Elimina el constructor con middleware
    // En Laravel 12, aplicamos los middlewares en las rutas

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            // Verificar UUID antes de completar el login
            $user = Auth::user();
            $uuid = $request->input('uuid');

            $uuid_check = Uuid::where('user_id', $user->id)
                            ->where('uuid', $uuid)
                            ->where('status', 1)
                            ->first();

            if ($uuid_check) {
                // Actualizar estado online
                $user->online_status = 1;
                $user->last_connection = now();
                $user->save();
                
                return $this->sendLoginResponse($request);
            } else {
                Auth::logout();
                return redirect()->back()
                    ->withInput($request->only('username'))
                    ->withErrors(['error' => 'Dispositivo no autorizado']);
            }
        }

        return $this->sendFailedLoginResponse($request);
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
            'uuid' => 'required|string|max:50',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        // Buscar el usuario por username y verificar password con md5
        $user = User::where('username', $request->input('username'))
                   ->where('user_status', 1)
                   ->first();

        if ($user && $user->password === md5($request->input('password'))) {
            Auth::login($user, $request->filled('remember'));
            return true;
        }

        return false;
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        
        // Redirigir al usuario a la página de inicio
        return redirect()->intended($this->redirectTo);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only('username'))
            ->withErrors(['error' => 'Usuario o Contraseña Incorrectos']);
    }
}