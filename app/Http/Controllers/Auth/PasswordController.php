<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;


class PasswordController extends Controller
{
    /**
     * Show the form to change the user's password.
     */
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Update the authenticated user's password.
     */
  public function update(Request $request)
{
    Log::info('Password update attempt by user ID: ' . Auth::id());

    // 1. Validate input
    $request->validate([
        'current_password' => ['required'],
        'new_password'     => ['required', 'confirmed', Password::defaults()],
    ]);

    $user = Auth::user();

    // 2. Verify current password
    if (! Hash::check($request->current_password, $user->password)) {
        Log::warning('Current password incorrect for user ID: ' . $user->id);
        return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }

    // 3. Update password + flag
    $user->password          = Hash::make($request->new_password);
    $user->password_changed  = true;
    $user->save();

    Log::info('Password updated successfully for user ID: ' . $user->id);

    // 4. Flash success message (for Toastr) and redirect
    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Password updated successfully');
}

}
