<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        $user->load('role'); // Eager load the role relationship
        
        if ($user->role) {
            // Student redirect
            if ($user->role->name === 'student') {
                return redirect()->intended(route('student.dashboard', absolute: false));
            }
            
            // Parent redirect
            if ($user->role->name === 'parent') {
                return redirect()->intended(route('parent.dashboard', absolute: false));
            }
            
            // Principal and Assistant Principal redirect
            if (in_array($user->role->name, ['principal', 'assistant_principal'])) {
                return redirect()->intended(route('principal.dashboard', absolute: false));
            }
            
            // Adviser redirect
            if ($user->role->name === 'adviser') {
                return redirect()->intended(route('adviser.dashboard', absolute: false));
            }
        }

        // Default redirect for admin or other roles
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
