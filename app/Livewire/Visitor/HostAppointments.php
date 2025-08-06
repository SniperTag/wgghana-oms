<?php

namespace App\Livewire\Visitor;

use App\Mail\AdminAppointmentRescheduledMail;
use App\Mail\AppointmentAcceptMail;
use App\Mail\AppointmentDeclinedMail;
use App\Models\Appointment;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class HostAppointments extends Component
{
    public $appointments;
    public $selectedAppointment;
    public $declineReason = '';
    public $declineModal = false;
    public $appointmentToDecline = null;
    public $selectedExpandedId = null;
    public $summary = [];
    public $rescheduleModal = false;
public $rescheduleAppointmentId;
public $newDate;
public $title;
public $newTime;
public $statusFilter = '';
public $approvedCount;
    public $declinedCount;
    public $wasRescheduledCount;
    public $totalCount;
    protected $listeners = [
        'appointmentUpdated' => 'loadAppointments',
        'appointmentAccepted' => 'loadAppointments',
        'appointmentDeclined' => 'loadAppointments',
        'appointmentRescheduled' => 'loadAppointments',
    ];

    public function mount()
    {
        $this->appointments = collect();
        $this->loadAppointments();
    }

 public function loadAppointments()
{
    // Reset rescheduled to pending if needed
    $appointmentsToReset = Appointment::where('status', 'rescheduled')
        ->where('rescheduled_at', '<=', now()->subMinutes(5))
        ->get();

    foreach ($appointmentsToReset as $appointment) {
        $appointment->update(['status' => 'pending']);
        Log::info("Appointment #{$appointment->id} reverted to pending after reschedule delay");
    }

    // Load fresh appointments
    $query = Appointment::where('host_id', Auth::id())
        ->orderBy('date', 'asc');

    if ($this->statusFilter !== '') {
        $query->where('status', $this->statusFilter);
    }

    $this->appointments = $query->get();

    // Update count properties
    $this->approvedCount = $this->appointments->where('status', 'approved')->count();
    $this->declinedCount = $this->appointments->where('status', 'cancelled')->count();
    $this->wasRescheduledCount = $this->appointments->where('was_rescheduled', true)->count();
    $this->totalCount = $this->appointments->count();
}

    public function updatedStatusFilter()
{
    $this->loadAppointments();
}


    public function updatedDeclineReason()
    {
        $this->resetErrorBag('declineReason');
    }


    //This Method is Decline Appointment By Host
    public function confirmDecline($id)
    {
        $this->appointmentToDecline = $id;
        $this->declineModal = true;
        $this->openModal('decline-appointment');
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

        Log::info("Appointment accepted", [
            'host_id' => Auth::id(),
            'appointment_id' => $id
        ]);

        if ($appointment->visitor_email) {
            Mail::to($appointment->visitor_email)->send(new AppointmentAcceptMail($appointment));
        }

        if ($appointment->visitor_phone) {
            try {
                $sms = new SmsService();
                $sms->send($appointment->visitor_phone, 
                    "Hello {$appointment->visitor_name}, your appointment on {$appointment->date->format('M d, Y h:i A')} has been accepted. Thank you - Waltergates Ghana Ltd");
            } catch (\Exception $e) {
                Log::error("Failed to send SMS (accept): " . $e->getMessage());
            }
        }

        $this->loadAppointments();
         session()->flash('message', 'Appointment accepted and visitor notified.');
       
    }



    public function declineConfirmed()
    {
        $this->validate([
            'declineReason' => 'required|min:5',
        ]);

        $appointment = Appointment::findOrFail($this->appointmentToDecline);

        $appointment->update([
            'status' => 'cancelled',
            'decline_reason' => $this->declineReason,
        ]);

        Log::info("Appointment declined", [
            'host_id' => Auth::id(),
            'appointment_id' => $appointment->id,
            'reason' => $this->declineReason,
        ]);

        if ($appointment->visitor_email) {
            Mail::to($appointment->visitor_email)->send(new AppointmentDeclinedMail($appointment));
        }

        if ($appointment->visitor_phone) {
            try {
                $sms = new SmsService();
                $sms->send($appointment->visitor_phone,
                    "Hello {$appointment->visitor_name}, your appointment on {$appointment->date->format('M d, Y h:i A')} was declined. Reason: {$this->declineReason} - Waltergates Ghana Ltd");
            } catch (\Exception $e) {
                Log::error("Failed to send SMS (decline): " . $e->getMessage());
            }
        }

        $this->declineModal = false;
        $this->appointmentToDecline = null;
        $this->declineReason = '';

        $this->closeDeclineModal();

        $this->loadAppointments();
        session()->flash('message', 'Appointment declined and visitor notified.');
    }


    public function showRescheduleModal($id)
{
    $this->rescheduleAppointmentId = $id;
    $this->rescheduleModal = true;
        $this->openModal('reschedule-appointment');


    $appointment = Appointment::find($id);
    $this->newDate = optional($appointment->date)->format('Y-m-d');
    $this->newTime = optional($appointment->date)->format('H:i');
}

public function closeRescheduleModal()
{
    $this->rescheduleModal = false;
    $this->rescheduleAppointmentId = null;
    $this->newDate = null;
    $this->newTime = null;
    $this->closeModal('reschedule-appointment');
}

public function closeDeclineModal()
{
    $this->declineModal = false;
    $this->appointmentToDecline = null;
    $this->declineReason = '';
    $this->closeModal('decline-appointment');
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
            $dayOfWeek = Carbon::parse($value)->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
            if ($dayOfWeek > 5) {
                $fail('Appointments can only be rescheduled to weekdays (Monday to Friday).');
            }
        }],
        'newTime' => 'required',
    ]);

    $newDateTime = Carbon::parse("{$this->newDate} {$this->newTime}");

    // Check if new datetime is at least 30 minutes from now
    $minAllowed = Carbon::now()->addMinutes(30);
    if ($newDateTime->lessThan($minAllowed)) {
        $this->addError('newTime', 'Appointment time must be at least 30 minutes from now.');
        return;
    }

    $appointment = Appointment::findOrFail($this->rescheduleAppointmentId);

    // Optional: Check for overlapping appointments for this host at newDateTime
    $overlap = Appointment::where('host_id', $appointment->host_id)
                ->where('id', '!=', $appointment->id)
                ->where('date', $newDateTime)
                ->exists();
    if ($overlap) {
        $this->addError('newTime', 'You already have another appointment scheduled at this date and time.');
        return;
    }

    $oldDateTime = $appointment->date;

    $appointment->update([
        'date' => $newDateTime,
        'status' => 'rescheduled', // Reset status
        'was_rescheduled' => true, // Mark as rescheduled
        'rescheduled_at' => now(), // Store rescheduled timestamp
        'rescheduled_by' => Auth::id(), // Store who rescheduled
        'reschedule_reason' => 'Rescheduled by host', // Optional reason
    ]);

    Log::info("Appointment rescheduled", [
        'host_id' => Auth::id(),
        'appointment_id' => $appointment->id,
        'old_date' => $oldDateTime,
        'new_date' => $newDateTime,
    ]);

    // Email visitor about new date/time
    if ($appointment->visitor_email) {
        Mail::to($appointment->visitor_email)->send(new AppointmentAcceptMail($appointment));
    }

    // SMS visitor about new date/time
    if ($appointment->visitor_phone) {
        try {
            $sms = new SmsService();
            $sms->send($appointment->visitor_phone,
                "Hello {$appointment->visitor_name}, your appointment has been rescheduled to {$appointment->date->format('M d, Y h:i A')} - Waltergates Ghana Ltd");
        } catch (\Exception $e) {
            Log::error("Failed to send SMS (reschedule): " . $e->getMessage());
        }
    }

    // Notify Admin - replace with your admin email or a config value
    $adminEmail = config('mail.admin_email', 'admin@example.com');
    Mail::to($adminEmail)->send(new AdminAppointmentRescheduledMail($appointment, $oldDateTime));

    $this->rescheduleModal = false;
    $this->rescheduleAppointmentId = null;
    $this->newDate = null;
    $this->newTime = null;


      $this->closeRescheduleModal();
    $this->loadAppointments();
    session()->flash('message', 'Appointment rescheduled and visitor & admin notified.');
}


    //Summary to account approved, declined and rescheduled appointments
    
public function render()
{
    return view('livewire.visitor.host-appointments', [
        'appointments' => $this->appointments,
        'approvedCount' => $this->approvedCount,
        'declinedCount' => $this->declinedCount,
        'rescheduledCount' => $this->wasRescheduledCount,
        'totalCount' => $this->totalCount,
        'selectedExpandedId' => $this->selectedExpandedId,
    ])->layout('components.layouts.visit');
}

}
