<?php

namespace App\Http\Controllers\Auth;

use session;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

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
    // Authenticate the user
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    // Redirect to face enrollment if face image is not set
    if (is_null($user->face_image)) {
        return redirect()->route('face.enroll')->with([
            'message' => 'Please enroll your face for attendance tracking.',
        ]);
    }

    // Map roles to their dashboard routes (must match route names in web.php)
    $roleRedirectMap = [
        'super_admin' => 'super_admin.page',
        'admin'       => 'admin.dashboard',
        'hr'          => 'hr.dashboard',
        'manager'     => 'manager.dashboard',
        'finance'     => 'finance.dashboard',
        'supervisor'  => 'supervisor.dashboard',
        'staff'       => 'staff.dashboard',
    ];

    // Role priority order (highest to lowest)
    $rolePriority = ['super_admin', 'admin', 'hr', 'manager', 'finance', 'supervisor', 'staff'];

    $redirectRoute = null;

    // Check user roles in priority order
    foreach ($rolePriority as $role) {
        if ($user->hasRole($role)) {
            $redirectRoute = $roleRedirectMap[$role];
            \Log::info("User {$user->name} matched role: {$role} → redirecting to: {$redirectRoute}");
            break; // first matching role wins
        }
    }

    // Logout if no valid role found
    if (!$redirectRoute) {
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Your account has no assigned role or unauthorized role.',
        ]);
    }

    // Redirect to the correct dashboard
    return redirect()->route($redirectRoute)->with('success', 'Successfully logged in, ' . $user->name . '!');
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
