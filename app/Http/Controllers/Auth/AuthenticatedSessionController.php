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

        $user = Auth::user();


        // Redirect to face enrollment if face image not set
        if (is_null($user->face_image)) {
            return redirect()->route('face.enroll')->with([
                'message' => 'Please enroll your face for attendance tracking.',
            ]);
        }

        // Role-based redirection using Spatie
        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard');
        } elseif ($user->hasRole('hr')) {
            return redirect()->route('hr.dashboard');
        } elseif ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->hasRole('finance')) {
            return redirect()->route('finance.dashboard');
        } elseif ($user->hasRole('supervisor')) {
            return redirect()->route('supervisor.dashboard');
        } elseif ($user->hasRole('staff')) {
            return redirect()->route('staff.dashboard');
        }

        // Default fallback if no role matched
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Your account has no assigned role or unauthorized role.',
        ]);
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
