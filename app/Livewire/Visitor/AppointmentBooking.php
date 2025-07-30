<?php

namespace App\Livewire\Visitor;

use App\Models\User;
use App\Models\Visitor;
use Livewire\Component;
use App\Models\Appointment;
use App\Models\Department;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Notifications\AppointmentScheduledNotification;

class AppointmentBooking extends Component
{
    public $visitor_ids = [];
    public $user_id;
    public $department_id;
    public $scheduled_at;
    public $purpose;
    public $status = 'pending';
    public $searchId = '';
    public $searchResults;
    public $filter_status = 'all';

    protected $rules = [
        'visitor_ids' => 'required|array|min:1',
        'user_id' => 'required|exists:users,id',
        'department_id' => 'nullable|exists:departments,id',
        'scheduled_at' => 'required|date|after_or_equal:today',
        'purpose' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->searchResults = collect();
    }

   public function updatedSearchId()
{
    if (!empty($this->searchId)) {
        $this->searchResults = Visitor::where('id_number', 'like', '%' . $this->searchId . '%')
            ->orWhere('visitor_uid', 'like', '%' . $this->searchId . '%')
            ->orWhere('phone', 'like', '%' . $this->searchId . '%')
            ->orWhere('full_name', 'like', '%' . $this->searchId . '%')
            ->take(10)
            ->get();
    } else {
        $this->searchResults = collect();
    }
}


    public function selectVisitor($visitorId)
    {
        if (!in_array($visitorId, $this->visitor_ids)) {
            $this->visitor_ids[] = $visitorId;
        }
        $this->searchId = '';
        $this->searchResults = collect();
    }

    public function removeVisitor($visitorId)
{
    $this->visitor_ids = array_filter($this->visitor_ids, function ($id) use ($visitorId) {
        return $id != $visitorId;
    });
}


public function create()
{
    $this->validate();

    $appointment = Appointment::create([
        'user_id' => $this->user_id,
        'department_id' => $this->department_id,
        'scheduled_at' => $this->scheduled_at,
        'purpose' => $this->purpose,
        'status' => $this->status,
        'created_by' => Auth::id(),
    ]);

    $appointment->visitors()->attach($this->visitor_ids);

    // ✅ Generate QR Code and save as file
    $qrUrl = route('appointments.checkin', $appointment->id);
    $fileName = 'qrcodes/appointment_'.$appointment->id.'.svg';
    \Storage::disk('public')->put($fileName, QrCode::format('svg')->size(250)->generate($qrUrl));

    $appointment->update(['qr_code' => $fileName]);

    // ✅ Send notifications
    $smsService = new SmsService();
    foreach ($appointment->visitors as $visitor) {
        if ($visitor->email) {
            try {
                $visitor->notify(new AppointmentScheduledNotification($appointment));
            } catch (\Exception $e) {
                \Log::error("Email failed: {$visitor->email}", ['error' => $e->getMessage()]);
            }
        }

        if ($visitor->phone) {
            try {
                $message = "Hi {$visitor->full_name}, your appointment is on {$appointment->scheduled_at->format('d M Y h:i A')}.";
                $smsService->send($visitor->phone, $message);
            } catch (\Exception $e) {
                \Log::error("SMS failed: {$visitor->phone}", ['error' => $e->getMessage()]);
            }
        }
    }

    return redirect()->route('appointments.checkin', $appointment->id);
}




   public function render()
{
    $appointments = Appointment::query()
        ->when($this->filter_status !== 'all', fn($q) => $q->where('status', $this->filter_status))
        ->with(['visitors', 'user'])
        ->latest()
        ->get();

    $todayAppointments = Appointment::with(['visitors', 'user'])
        ->whereDate('scheduled_at', now()->toDateString())
        ->orderBy('scheduled_at')
        ->get();

    return view('livewire.visitor.appointment-booking', [
        'visitors' => Visitor::all(),
        'users' => User::all(),
        'departments' => Department::all(),
        'appointments' => $appointments,
        'todayAppointments' => $todayAppointments,  // <-- Add this
    ])->layout('layouts.livewire.appointment-booking');
}


    public function getStatusBadgeColor($status): string
    {
        switch ($status) {
            case 'pending': return 'yellow';
            case 'approved': return 'blue';
            case 'rejected': return 'red';
            case 'cancelled': return 'gray';
            case 'expired': return 'orange';
            case 'completed': return 'green';
            default: return 'gray';
        }
    }
}
