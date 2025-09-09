<?php

namespace App\Livewire\Visitor;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\VisitLog;
use App\Models\Appointment;
use App\Services\SmsService;
use App\Mail\AppointmentAcceptMail;
use App\Mail\AppointmentDeclinedMail;
use App\Mail\AdminAppointmentRescheduledMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HostAppointments extends Component
{
    public $appointments;
    public $selectedAppointment;
    public $declineReason = '';
    public $declineModal = false;
    public $appointmentToDecline = null;
    public $selectedExpandedId = null;
    public $rescheduleModal = false;
    public $rescheduleAppointmentId = null;
    public $newDate = null;
    public $title = '';
    public $newTime = null;
    public $statusFilter = '';
    public $approvedCount = 0;
    public $declinedCount = 0;
    public $wasRescheduledCount = 0;
    public $totalCount = 0;
    public $checkInCount = 0;
    public $declinedCheckInCount = 0;
    public $totalVisitorCount = 0;
    public $todayCheckInCount = 0;

    public $visitLogs;

    protected $listeners = [
        'appointmentUpdated' => 'loadAppointments',
        'appointmentAccepted' => 'loadAppointments',
        'appointmentDeclined' => 'loadAppointments',
        'appointmentRescheduled' => 'loadAppointments',
    ];

    protected $modalNames = [
        'reschedule' => 'reschedule-appointment',
        'decline' => 'decline-appointment',
    ];

    public function mount()
    {
        $this->appointments = collect();
        $this->loadAppointments();
        $this->loadVisitLogs();
        $this->loadCheckInCounts();
    }

    public function loadAppointments()
    {
        try {
            $appointmentsToReset = Appointment::where('status', 'rescheduled')
                ->where('rescheduled_at', '<=', now()->subMinutes(5))
                ->get();

            foreach ($appointmentsToReset as $appointment) {
                $appointment->update(['status' => 'pending']);
                Log::info("Appointment #{$appointment->id} reverted to pending after reschedule delay");
            }

            $query = Appointment::where('host_id', Auth::id())->orderBy('date', 'asc');

            if ($this->statusFilter !== '') {
                $query->where('status', $this->statusFilter);
            }

            $this->appointments = $query->get();
            $this->loadCounts();
        } catch (\Exception $e) {
            Log::error("Error loading appointments: " . $e->getMessage());
            $this->appointments = collect();
            $this->approvedCount = 0;
            $this->declinedCount = 0;
            $this->wasRescheduledCount = 0;
            $this->totalCount = 0;
        }
    }

    public function loadVisitLogs()
    {
        try {
            $this->visitLogs = VisitLog::with('visitor')->latest()->get();
        } catch (\Exception $e) {
            Log::error("Error loading visit logs: " . $e->getMessage());
            $this->visitLogs = collect();
        }
    }

    public function loadCheckInCounts()
    {
        try {
            $hostId = Auth::id();
            $this->checkInCount = VisitLog::where('host_id', $hostId)
                ->where('approval_status', 'approved')
                ->where('status', 'checked_in')
                ->count();

            $this->declinedCheckInCount = VisitLog::where('host_id', $hostId)
                ->where('approval_status', 'rejected')
                ->where('status', 'cancelled')
                ->count();

            $this->totalVisitorCount = VisitLog::where('host_id', $hostId)
                ->whereIn('approval_status', ['approved', 'rejected'])
                ->count();

            $today = Carbon::today();
            $this->todayCheckInCount = VisitLog::where('host_id', $hostId)
                ->whereDate('check_in_time', $today)
                ->where('approval_status', 'approved')
                ->where('status', 'checked_in')
                ->count();

            Log::info("Check-in counts loaded", [
                'check_in_count' => $this->checkInCount,
                'declined-check_in_count' => $this->declinedCheckInCount,
                'total_visitor_count' => $this->totalVisitorCount,
            ]);
        } catch (\Exception $e) {
            Log::error("Error loading check-in counts: " . $e->getMessage());
            $this->checkInCount = 0;
            $this->declinedCheckInCount = 0;
            $this->totalVisitorCount = 0;
            $this->todayCheckInCount = 0;
        }
    }

    public function loadCounts()
    {
        try {
            $hostId = Auth::id();
            $this->approvedCount = Appointment::where('host_id', $hostId)->where('status', 'approved')->count();
            $this->declinedCount = Appointment::where('host_id', $hostId)->where('status', 'cancelled')->count();
            $this->wasRescheduledCount = Appointment::where('host_id', $hostId)->where('was_rescheduled', true)->count();
            $this->totalCount = Appointment::where('host_id', $hostId)->count();

            $this->loadCheckInCounts();
        } catch (\Exception $e) {
            Log::error("Error loading counts: " . $e->getMessage());
            $this->approvedCount = 0;
            $this->declinedCount = 0;
            $this->wasRescheduledCount = 0;
            $this->totalCount = 0;
        }
    }

    public function updatedStatusFilter()
    {
        $this->loadAppointments();
    }

    public function updatedDeclineReason()
    {
        $this->resetErrorBag('declineReason');
    }

    public function confirmDecline($id)
    {
        $this->appointmentToDecline = $id;
        $this->declineModal = true;
        $this->openModal($this->modalNames['decline']);
    }

    public function toggleExpand($id)
    {
        $this->selectedExpandedId = $this->selectedExpandedId === $id ? null : $id;
    }

    public function selectAppointment($id)
    {
        $this->selectedAppointment = Appointment::find($id);
    }

    public function accept($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'approved']);
        Log::info("Appointment accepted", ['host_id' => Auth::id(), 'appointment_id' => $id]);

        if ($appointment->visitor_email) {
            try { Mail::to($appointment->visitor_email)->send(new AppointmentAcceptMail($appointment)); } 
            catch (\Exception $e) { Log::error("Email failed (accept): " . $e->getMessage()); }
        }

        if ($appointment->visitor_phone) {
            try {
                $sms = new SmsService();
                $sms->send($appointment->visitor_phone,
                    "Hello {$appointment->visitor_name}, your appointment on {$appointment->date->format('M d, Y h:i A')} has been accepted. Thank you - Waltergates Ghana Ltd");
            } catch (\Exception $e) {
                Log::error("SMS failed (accept): " . $e->getMessage());
            }
        }

        $this->loadAppointments();
        $this->dispatch('toast', ['type'=>'success','message'=>'Appointment accepted and visitor notified.']);
    }

    public function declineConfirmed()
    {
        $this->validate(['declineReason' => 'required|min:5']);

        $appointment = Appointment::findOrFail($this->appointmentToDecline);
        $appointment->update(['status' => 'cancelled','decline_reason' => $this->declineReason]);

        Log::info("Appointment declined", ['host_id' => Auth::id(),'appointment_id' => $appointment->id,'reason' => $this->declineReason]);

        if ($appointment->visitor_email) {
            try { Mail::to($appointment->visitor_email)->send(new AppointmentDeclinedMail($appointment)); }
            catch (\Exception $e) { Log::error("Email failed (decline): " . $e->getMessage()); }
        }

        if ($appointment->visitor_phone) {
            try {
                $sms = new SmsService();
                $sms->send($appointment->visitor_phone,
                    "Hello {$appointment->visitor_name}, your appointment on {$appointment->date->format('M d, Y h:i A')} was declined. Reason: {$this->declineReason} - Waltergates Ghana Ltd");
            } catch (\Exception $e) {
                Log::error("SMS failed (decline): " . $e->getMessage());
            }
        }

        $this->closeDeclineModal();
        $this->loadAppointments();
        $this->dispatch('toast', ['type'=>'success','message'=>'Appointment declined and visitor notified.']);
    }

    public function showRescheduleModal($id)
    {
        $this->rescheduleAppointmentId = $id;
        $this->rescheduleModal = true;
        $this->openModal($this->modalNames['reschedule']);

        $appointment = Appointment::find($id);
        $this->newDate = optional($appointment->date)->format('Y-m-d');
        $this->newTime = optional($appointment->date)->format('H:i');
    }

    public function closeRescheduleModal()
    {
        $this->resetModals();
        $this->closeModal($this->modalNames['reschedule']);
    }

    public function closeDeclineModal()
    {
        $this->resetModals();
        $this->closeModal($this->modalNames['decline']);
    }

    public function resetModals()
    {
        $this->declineModal = false;
        $this->rescheduleModal = false;
        $this->appointmentToDecline = null;
        $this->rescheduleAppointmentId = null;
        $this->declineReason = '';
        $this->newDate = null;
        $this->newTime = null;
    }

    public function openModal(string $modalName)
    {
        $this->dispatch('openModal', $modalName);
    }

    public function closeModal(string $modalName)
    {
        $this->dispatch('closeModal', $modalName);
    }

    public function rescheduleConfirmed()
    {
        $this->validate([
            'newDate' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->dayOfWeekIso > 5) {
                    $fail('Appointments can only be rescheduled to weekdays (Monday to Friday).');
                }
            }],
            'newTime' => 'required',
        ]);

        $newDateTime = Carbon::parse("{$this->newDate} {$this->newTime}");
        if ($newDateTime->lessThan(now()->addMinutes(30))) {
            $this->addError('newTime','Appointment time must be at least 30 minutes from now.');
            return;
        }

        $appointment = Appointment::findOrFail($this->rescheduleAppointmentId);

        $overlap = Appointment::where('host_id', $appointment->host_id)
                    ->where('id','!=',$appointment->id)
                    ->where('date', $newDateTime)
                    ->exists();

        if ($overlap) {
            $this->addError('newTime','You already have another appointment scheduled at this date and time.');
            Log::warning("Reschedule conflict for host_id: {$appointment->host_id} at {$newDateTime}");
            return;
        }

        $oldDateTime = $appointment->date;

        $appointment->update([
            'date' => $newDateTime,
            'status' => 'rescheduled',
            'was_rescheduled' => true,
            'rescheduled_at' => now(),
            'rescheduled_by' => Auth::id(),
            'reschedule_reason' => 'Rescheduled by host',
        ]);

        Log::info("Appointment rescheduled", [
            'host_id' => Auth::id(),
            'appointment_id' => $appointment->id,
            'old_date' => $oldDateTime,
            'new_date' => $newDateTime,
        ]);

        if ($appointment->visitor_email) {
            try { Mail::to($appointment->visitor_email)->send(new AppointmentAcceptMail($appointment)); } 
            catch (\Exception $e) { Log::error("Email failed (reschedule): " . $e->getMessage()); }
        }

        if ($appointment->visitor_phone) {
            try {
                $sms = new SmsService();
                $sms->send($appointment->visitor_phone,
                    "Hello {$appointment->visitor_name}, your appointment has been rescheduled to {$appointment->date->format('M d, Y h:i A')} - Waltergates Ghana Ltd");
            } catch (\Exception $e) {
                Log::error("SMS failed (reschedule): " . $e->getMessage());
            }
        }

        $adminEmail = config('mail.admin_email', null);
        if ($adminEmail) {
            try { Mail::to($adminEmail)->send(new AdminAppointmentRescheduledMail($appointment,$oldDateTime)); }
            catch (\Exception $e) { Log::error("Admin email failed: " . $e->getMessage()); }
        }

        $this->loadAppointments();
        $this->closeRescheduleModal();
        $this->dispatch('toast', ['type'=>'success','message'=>'Appointment rescheduled and visitor & admin notified.']);
    }

    public function render()
    {
        return view('livewire.visitor.host-appointments')
            ->layout('components.layouts.visit');
    }
}
