<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario demo
     */
    public function register(Request $request)
    {
        // Validar datos del formulario
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,username|max:255',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        try {
            // Crear usuario demo
            $user = User::create([
                'username' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => 3, // Demo role
                'level' => 4, // Demo level
                'user_status' => true,
                'online_status' => false,
            ]);

            // Auto-login después del registro
            Auth::login($user);

            // Actualizar estado online
            $user->online_status = true;
            $user->last_connection = now();
            $user->save();

            // Redirigir al home
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('home')
                ]);
            }

            return redirect()->route('home')->with('success', '¡Registro exitoso! Bienvenido a la versión demo.');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al crear la cuenta: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Error al crear la cuenta. Por favor, intenta nuevamente.']);
        }
    }
}
