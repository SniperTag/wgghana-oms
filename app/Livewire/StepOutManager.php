<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Models\StepOut;
use App\Models\BreakSession;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Events\StaffSteppedOut;
use App\Events\StaffReturned;
use App\Notifications\StepOutNotification;

class StepOutManager extends Component
{
    public $actionType = 'step_out'; // 'step_out' or 'break'
    public $reason = '';
    public $breakType = '';
    public bool $currentlySteppedOut = false;
    public bool $currentlyOnBreak = false;
    public $breakTypes = ['Lunch', 'Coffee', 'Prayer', 'Personal'];

    public function mount()
    {
        Log::info('StepOutManager mounted for user: ' . Auth::id());
        $this->updateStatuses();
    }

    // Update step out / break status
    public function updateStatuses()
    {
        $this->currentlySteppedOut = StepOut::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->exists();

        $this->currentlyOnBreak = BreakSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->exists();

        Log::info('Statuses updated', [
            'currentlySteppedOut' => $this->currentlySteppedOut,
            'currentlyOnBreak' => $this->currentlyOnBreak,
        ]);
    }

    // Start Step Out or Break
    public function startAction()
    {
        Log::info('startAction called', [
            'actionType' => $this->actionType,
            'reason' => $this->reason,
            'breakType' => $this->breakType,
        ]);

        try {
            if ($this->actionType === 'step_out') {
                if (!$this->reason) return $this->notify('error', 'Please provide a reason.');

                StepOut::create([
                    'user_id' => Auth::id(),
                    'reason' => $this->reason,
                    'stepped_out_at' => now(),
                    'status_code' => 'NAV',
                ]);

                event(new StaffSteppedOut(Auth::user()));
                $this->notifyUsers('stepped out');

            } elseif ($this->actionType === 'break') {
                if (!$this->breakType) return $this->notify('error', 'Please select a break type.');

                $attendanceId = AttendanceRecord::where('user_id', Auth::id())
                    ->whereDate('created_at', today())
                    ->value('id');

                if (!$attendanceId) return $this->notify('error', 'No attendance record found today.');

                BreakSession::create([
                    'user_id' => Auth::id(),
                    'attendance_id' => $attendanceId,
                    'started_at' => now(),
                    'break_type' => $this->breakType,
                    'status_code' => 'NAV',
                ]);

                event(new StaffSteppedOut(Auth::user()));
                $this->notifyUsers("started a {$this->breakType} break");
            } else {
                return $this->notify('error', 'Invalid action type.');
            }

            $this->updateStatuses();
            $this->notify('success', 'Action started successfully!');
            $this->closeModal();
            $this->resetInputs();

        } catch (\Exception $e) {
            Log::error('Error in startAction: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $this->notify('error', 'Something went wrong.');
        }
    }

    // Return from Step Out
    public function returnBack()
    {
        $stepOut = StepOut::where('user_id', Auth::id())->whereNull('returned_at')->latest()->first();

        if (!$stepOut) return $this->notify('error', 'No active step out found.');

        $stepOut->update([
            'returned_at' => now(),
            'status_code' => 'AVL',
        ]);

        event(new StaffReturned(Auth::user()));
        $this->notifyUsers('returned');
        $this->updateStatuses();
        $this->notify('success', 'Welcome back!');
    }

    // End Break
    public function endBreak()
    {
        $breakSession = BreakSession::where('user_id', Auth::id())->whereNull('ended_at')->latest()->first();

        if (!$breakSession) return $this->notify('error', 'No active break found.');

        $breakSession->update([
            'ended_at' => now(),
            'break_duration' => now()->diffInMinutes($breakSession->started_at),
            'status_code' => 'AVL',
        ]);

        event(new StaffReturned(Auth::user()));
        $this->notifyUsers("ended {$breakSession->break_type} break");
        $this->updateStatuses();
        $this->notify('success', 'Break ended!');
    }

    // Reset only inputs (keep $actionType)
    protected function resetInputs()
    {
        $this->reason = '';
        $this->breakType = '';
    }

    protected function closeModal()
    {
        $this->dispatch('close-modal', ['id' => 'stepOutModal']);
    }

    protected function notifyUsers($action)
    {
        Notification::send(
            User::role(['super_admin', 'admin', 'manager', 'supervisor'])->get(),
            new StepOutNotification(Auth::user(), $action)
        );
    }

    protected function notify($type, $message)
    {
        $this->dispatch('notify', ['type' => $type, 'message' => $message]);
    }

    public function render()
    {
        return view('livewire.step-out-manager');
    }
}
