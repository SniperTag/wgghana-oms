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
     * Display a listing of the leave balances for a user.
     */

    public function index(User $user)
    {
        // Fetch leave balances for the user
        $leaveBalances = LeaveBalance::where('user_id', $user->id)
            ->with('leaveType')
            ->get();

        // Log the action
        Log::info('Leave balances viewed.', ['user_id' => $user->id]);

        return view('admin.leave_balances.index', compact('user', 'leaveBalances'));
    }

    /**
     * Show the form for creating a new leave balance for a user.
     */
    public function create()
    {
        // Get the authenticated user
         $users = User::all();
    $leaveTypes = LeaveType::all();
    return view('admin.leave_balances.create', compact('users', 'leaveTypes'));
    }

    /**
     * Store a newly created leave balance in storage.
     */
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'total_days' => 'required|numeric|min:0',
            'used_days' => 'required|numeric|min:0',
            'remaining_days' => 'required|numeric|min:0',
            'year' => 'required|integer|min:' . (now()->year - 10) . '|max:' . (now()->year + 10),
        ]);

        if ($validated['used_days'] > $validated['total_days']) {
            toastr()->error('Used days cannot exceed total days.');
            return back()->withInput();
        }

        $exists = LeaveBalance::where([
            'user_id' => $validated['user_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'year' => $validated['year'],
        ])->exists();

        if ($exists) {
            toastr()->warning('A leave balance for this type and year already exists for the selected user.');
            return back()->withInput();
        }

        LeaveBalance::create([
            'user_id' => $validated['user_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'total_days' => $validated['total_days'],
            'used_days' => $validated['used_days'],
            'remaining_days' => $validated['total_days'] - $validated['used_days'],
            'year' => $validated['year'],
        ]);

        Log::info('Leave balance created', [
            'user_id' => $validated['user_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'year' => $validated['year'],
            'created_by' => Auth::id(),
        ]);

        toastr()->success('Leave balance created successfully.');
        return redirect()->route('admin.users_index');

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
