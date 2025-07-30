<?php

namespace App\Livewire\Visitor;

use App\Models\Appointment;
use App\Models\VisitLog;
use App\Notifications\AppointmentApprovedNotification;
use Livewire\Component;

class AppointmentCheckin extends Component
{
    public $appointment;
    public $checkedIn = false;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        // Determine initial state
        $this->checkedIn = VisitLog::where('appointment_id', $appointment->id)
            ->where('status', 'checked_in')
            ->exists();
    }

    public function checkIn()
    {
        $newLogs = 0;

        foreach ($this->appointment->visitors as $visitor) {
            $exists = VisitLog::where('appointment_id', $this->appointment->id)
                ->where('visitor_id', $visitor->id)
                ->where('status', 'checked_in')
                ->exists();

            if (!$exists) {
                VisitLog::create([
                    'visitor_id' => $visitor->id,
                    'appointment_id' => $this->appointment->id,
                    'status' => 'checked_in',
                    'logged_at' => now(),
                ]);
                $newLogs++;
            }
        }

        if ($newLogs === 0) {
            session()->flash('message', 'Visitor(s) already checked in.');
        } else {
            $this->appointment->update(['status' => 'pending']);
            $this->checkedIn = true;
            session()->flash('message', "Checked in {$newLogs} visitor(s).");
        }
    }

    public function checkOut()
    {
        $newLogs = 0;

        foreach ($this->appointment->visitors as $visitor) {
            $exists = VisitLog::where('appointment_id', $this->appointment->id)
                ->where('visitor_id', $visitor->id)
                ->where('status', 'checked_out')
                ->exists();

            if (!$exists) {
                VisitLog::create([
                    'visitor_id' => $visitor->id,
                    'appointment_id' => $this->appointment->id,
                    'status' => 'checked_out',
                    'logged_at' => now(),
                ]);
                $newLogs++;
            }
        }

        if ($newLogs === 0) {
            session()->flash('message', 'Visitor(s) already checked out.');
        } else {
            $this->appointment->update(['status' => 'completed']);
            session()->flash('message', "Checked out {$newLogs} visitor(s).");
        }
    }

    public function approveAppointment(Appointment $appointment)
    {
        $appointment->update(['status' => 'approved']);
        // Optionally notify visitors about approval here
        foreach ($appointment->visitors as $visitor) {
            if ($visitor->email) {
                try {
                    $visitor->notify(new AppointmentApprovedNotification($appointment));
                } catch (\Exception $e) {
                    \Log::error("Email failed: {$visitor->email}", ['error' => $e->getMessage()]);
                }
            }
        }
    }

    public function rejectAppointment(Appointment $appointment, $reason = null)
    {
        $appointment->update(['status' => 'rejected']);
        // Optionally save reason and notify visitors
    }


    public function render()
    {
        return view('livewire.visitor.appointment-checkin')
            ->layout('layouts.livewire.base');
    }
}
