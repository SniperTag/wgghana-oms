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

    public function stepOut()
    {
        $this->startAction();
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
            return $this->notify('error', 'Please provide a reason.');
        }

        StepOut::create([
            'user_id' => Auth::id(),
            'reason' => $this->reason,
            'stepped_out_at' => now(),
        ]);

        $this->notifyUsers('stepped out');
    }
    elseif ($this->actionType === 'break') {
        if (empty($this->breakType)) {
            return $this->notify('error', 'Please select a break type.');
        }

        $attendanceId = AttendanceRecord::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->value('id');

        if (!$attendanceId) {
            return $this->notify('error', 'No attendance record found for today.');
        }

        BreakSession::create([
            'user_id' => Auth::id(),
            'attendance_id' => $attendanceId,
            'started_at' => now(),
            'break_type' => $this->breakType,
        ]);

        $this->notifyUsers("started a {$this->breakType} break");
    }

    $this->checkStatuses();
    $this->notify('success', 'Action started successfully!');
    $this->closeModal();
    $this->resetInputs();

    // Redirect to mail page route
    $this->redirect(route('admin.attendance'));
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

                $this->notifyUsers('returned');
                $this->currentlySteppedOut = false;
                $this->checkStatuses();

                $this->notify('success', 'Welcome back!');
                $this->dispatch('refreshStepOutHistory');
                $this->closeModal();
            } else {
                $this->notify('error', 'No active step-out found.');
            }
        } catch (\Exception $e) {
            Log::error('Error during return: ' . $e->getMessage());
            $this->notify('error', 'Something went wrong.');
        }
    }

    public function endBreak()
    {
        try {
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
                $this->checkStatuses();
                $this->notify('success', 'Break ended!');
            } else {
                $this->notify('error', 'No active break found.');
            }
        } catch (\Exception $e) {
            Log::error('Error ending break: ' . $e->getMessage());
            $this->notify('error', 'Something went wrong.');
        }
    }

    protected function notifyUsers($action)
    {
        Notification::send(
            User::role(['admin', 'hr', 'supervisor'])->get(),
            new StepOutNotification(Auth::user(), $action)
        );
    }

    protected function notify($type, $message)
    {
        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.step-out-manager');
    }
}
