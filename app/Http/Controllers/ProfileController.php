<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the user profile.
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Delete the user account.
     */
    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Optional: Require password confirmation before deletion (Security Best Practice)
        // But for "quick fix" for rejection, basic deletion is the core requirement.
        // I will implement password check if it's easy, but standard flow often asks for it.
        // Let's implement it to be safe and professional.
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Handled by database cascade on delete, but good to double check model relations if logic gets complex.

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
    }
}
