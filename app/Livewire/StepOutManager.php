<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StepOut;
use App\Models\BreakSession;
use App\Models\AttendanceRecord;
use App\Models\User;
use App\Notifications\StepOutNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StepOutManager extends Component
{
    public $actionType = 'step_out'; // step_out or break
    public $reason;
    public $breakType;
    public $resetInputs;
    public bool $currentlySteppedOut = false;
    public bool $currentlyOnBreak = false;
    public $breakTypes = ['Lunch', 'Coffee', 'Prayer', 'Personal'];

    public function mount()
    {
        $this->checkStatuses();
    }
    
    protected function closeModal()
    {
        $this->dispatch('close-modal', ['id' => 'stepOutModal']);
    }

    protected function resetInputs()
{
    $this->actionType = null;
    $this->reason = '';
    $this->breakType = '';
}


    public function checkStatuses()
    {
        $this->currentlySteppedOut = StepOut::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->exists();

        $this->currentlyOnBreak = BreakSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->exists();
    }

    public function startAction()
{
    if ($this->actionType === 'step_out') {
        if (empty($this->reason)) {
            return $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please provide a reason.'
            ]);
        }
        StepOut::create([
            'user_id' => Auth::id(),
            'reason' => $this->reason,
            'stepped_out_at' => now(),
        ]);
    } elseif ($this->actionType === 'break') {
        if (empty($this->breakType)) {
            return $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select a break type.'
            ]);
        }
        BreakSession::create([
            'user_id' => Auth::id(),
            'break_type' => $this->breakType,
            'started_at' => now(),
        ]);
    }

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Action started successfully!'
    ]);

    $this->dispatch('close-modal', ['id' => 'stepOutModal']);

    $this->resetInputs();
}


    protected function handleStepOut()
    {
        if (empty($this->reason)) {
            return $this->notify('error', 'Please provide a reason for stepping out.');
        }

        try {
            StepOut::create([
                'user_id' => Auth::id(),
                'stepped_out_at' => now(),
                'reason' => $this->reason,
            ]);

            $this->notifyUsers('stepped out');

            $this->resetInputs();
            $this->checkStatuses();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Safe journey, return safe!'
            ]);
            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Error during step out: ' . $e->getMessage());
            $this->dispatch('notify',['type'=>'error', 'message'=>'Something went wrong.']);
        }
    }

    protected function handleBreakStart()
{
    if (empty($this->breakType)) {
        return $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'Please select a break type.'
        ]);
    }

    try {
        $attendanceId = AttendanceRecord::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->value('id');

        BreakSession::create([
            'user_id' => Auth::id(),
            'attendance_id' => $attendanceId,
            'started_at' => now(),
            'break_type' => $this->breakType,
        ]);

        // Notify others via broadcast or Livewire event
        $this->notifyUsers("started a {$this->breakType} break");

        // Reset form input
        $this->resetInputs();
        $this->checkStatuses();

        // ✅ Trigger Toastr
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Enjoy your break!'
        ]);

        // ✅ Close the modal
        $this->dispatch('close-modal', [
            'id' => 'breakModal' // The modal's ID in Blade
        ]);

    } catch (\Exception $e) {
        Log::error('Error starting break: ' . $e->getMessage());

        // ✅ Error notification
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'Could not start break.'
        ]);
    }
}


    public function returnBack()
{
    try {
        $stepOut = StepOut::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->latest()
            ->first();

        if ($stepOut) {
            $stepOut->update([
                'returned_at' => now(),
                'status' => 'Available'
            ]);

            // Notify other users in real-time (if using Pusher or broadcasting)
            $this->notifyUsers('returned');

            // Reset internal state
            $this->currentlySteppedOut = false;

            // ✅ Browser notification for success
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Welcome back!'
            ]);

            // ✅ Refresh history table (emit Livewire refresh)
            $this->dispatch('refreshStepOutHistory');

            // ✅ Close modal if open
            $this->dispatch('close-modal', ['id' => 'stepOutModal']);
        } else {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No active step-out found.'
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Error during return: ' . $e->getMessage());
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'Something went wrong.'
        ]);
    }
}

    public function endBreak()
    {
        $breakSession = BreakSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if ($breakSession) {
            $breakSession->update([
                'ended_at' => now(),
                'break_duration' => now()->diffInMinutes($breakSession->started_at),
            ]);

            $this->notifyUsers("ended {$breakSession->break_type} break");
            $this->currentlyOnBreak = false;
            $this->notify('success', 'Break ended!');
        }
    }

    protected function notifyUsers($action)
    {
        Notification::send(
            User::role(['admin', 'hr', 'supervisor'])->get(),
            new StepOutNotification(Auth::user(), $action)
        );
    }

    // protected function resetInputs()
    // {
    //     $this->reset(['reason', 'breakType']);
    // }

    protected function notify($type, $message)
    {
        $this->dispatch('notify', [
            'type' => $type, // success | error
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.step-out-manager');
    }
}
