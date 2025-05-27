<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LeaveBalanceController extends Controller
{
    /**
     * Show the form for creating a new leave balance for a user.
     */
    public function create(User $user)
    {
        $leaveTypes = LeaveType::all();
        return view('admin.leave_balances.create', compact('user', 'leaveTypes'));
    }

    /**
     * Store a newly created leave balance in storage.
     */
    public function store(Request $request, User $user)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'total_days' => 'required|numeric|min:0',
                'used_days' => 'required|numeric|min:0',
                'remaining_days' => 'required|numeric|min:0',
                'year' => 'required|integer|min:2000|max:2100',
            ]);

            // Prevent duplicate leave balances
            $exists = LeaveBalance::where([
                'user_id' => $user->id,
                'leave_type_id' => $validated['leave_type_id'],
                'year' => $validated['year'],
            ])->exists();

            if ($exists) {
                toastr()->warning('A leave balance for this type and year already exists.');
                return back()->withInput();
            }

            // Create leave balance
            LeaveBalance::create([
                'user_id' => $user->id,
                'leave_type_id' => $validated['leave_type_id'],
                'total_days' => $validated['total_days'],
                'used_days' => $validated['used_days'],
                'remaining_days' => $validated['remaining_days'],
                'year' => $validated['year'],
            ]);

            // Log success
            Log::info('Leave balance created.', [
                'user_id' => $user->id,
                'leave_type_id' => $validated['leave_type_id'],
                'year' => $validated['year'],
            ]);

            toastr()->success('Leave balance created successfully.');
            return redirect()->route('admin.users.index');

        } catch (\Exception $e) {
            Log::error('Error creating leave balance: ' . $e->getMessage());
            toastr()->error('An error occurred while creating the leave balance.');
            return back()->withInput();
        }
    }

    /**
     * Show modal for editing leave balance.
     */
    public function editModal($id)
    {
        $balance = LeaveBalance::with('user', 'leaveType')->findOrFail($id);
        return view('components.modals.edit-leave-balance', compact('balance'));
    }

    /**
     * Update the specified leave balance in storage.
     */
    public function update(Request $request, LeaveBalance $leaveBalance)
    {
        $request->validate([
            'total_days' => 'required|numeric|min:0',
            'used_days' => 'required|numeric|min:0',
            'remaining_days' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $leaveBalance->update([
            'total_days' => $request->total_days,
            'used_days' => $request->used_days,
            'remaining_days' => $request->remaining_days,
            'year' => $request->year,
        ]);

        Log::info("Leave balance updated", ['id' => $leaveBalance->id]);

        toastr()->success('Leave balance updated successfully.');
        return back();
    }
}
