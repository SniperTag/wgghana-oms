<?php

namespace App\Livewire\Visitor;

use App\Models\User;
use App\Models\Visitor;
use Livewire\Component;
use App\Models\VisitLog;
use App\Models\VisitorType;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VisitLogStatusNotification;

class VisitLogForm extends Component
{
    public $visitor_id;
    public $host_id;
    public $purpose;
    public $visit_reason_detail;
    public $visitor_type_id;
    public $appointment_id;
    public $badge_number;
    public $approval_status = 'pending'; // pending, approved, rejected
    public $rejection_reason;
    public $registered_ip;
    public $device_name;
    public $remarks;
    public $location;
    public $status = 'pending'; // could be similar to approval_status or you can unify
    // checked_in_by and checked_out_by set automatically

    protected $rules = [
        'visitor_id' => 'required|exists:visitors,id',
        'host_id' => 'required|exists:users,id',
        'purpose' => 'required|string|max:500',
        'visit_reason_detail' => 'nullable|string|max:1000',
        'visitor_type_id' => 'nullable|exists:visitor_types,id',
        'appointment_id' => 'nullable|exists:appointments,id',
        'badge_number' => 'nullable|string|max:100',
        'approval_status' => 'required|in:pending,approved,rejected',
        'rejection_reason' => 'required_if:approval_status,rejected|string|max:500',
        'registered_ip' => 'nullable|ip',
        'device_name' => 'nullable|string|max:255',
        'remarks' => 'nullable|string|max:1000',
        'location' => 'nullable|string|max:255',
        'status' => 'required|in:pending,checked_in,checked_out,cancelled',
    ];

    public function updatedApprovalStatus($value)
    {
        if ($value !== 'rejected') {
            $this->rejection_reason = null;
        }
    }

    public function submit()
    {
        $this->validate();

        $visitLog = VisitLog::create([
            'visitor_id' => $this->visitor_id,
            'host_id' => $this->host_id,
            'purpose' => $this->purpose,
            'visit_reason_detail' => $this->visit_reason_detail,
            'visitor_type_id' => $this->visitor_type_id,
            'appointment_id' => $this->appointment_id,
            'badge_number' => $this->badge_number,
            'approval_status' => $this->approval_status,
            'rejection_reason' => $this->approval_status === 'rejected' ? $this->rejection_reason : null,
            'registered_ip' => $this->registered_ip,
            'device_name' => $this->device_name,
            'remarks' => $this->remarks,
            'location' => $this->location,
            'status' => $this->status,
            'check_in_time' => $this->approval_status === 'approved' ? now() : null,
            'checked_in_by' => $this->approval_status === 'approved' ? Auth::id() : null,
            'created_by' => Auth::id(),
        ]);

        // Notify host and visitor
        $visitor = Visitor::find($this->visitor_id);
        $host = User::find($this->host_id);
// ✅ Notify Host
    if ($visitor->host_id) {
        $host = User::find($visitor->host_id);
        if ($host) {
            $host->notify(new VisitLogStatusNotification($visitor));

            if ($host->phone) {
                $hostSms = "Hello {$host->full_name}, a visitor is here to see you: {$visitor->full_name}. UID: {$visitor->visitor_uid}.";


                $smsService = app()->make('App\Services\SmsService');

                $response = $smsService->send($host->phone, $hostSms);

                if (!$response['success']) {
                    toastr()->error("SMS to host failed: {$response['status']}");
                    Log::warning("Host SMS failed", $response);
                } else {
                    toastr()->success("SMS sent to host: {$response['status']}");
                }
            }
        }
    }

    // ✅ Notify Visitor (Email + SMS)
    if ($visitor->email) {
        $visitor->notify(new VisitLogStatusNotification($visitor));
    }


        try {
            $host?->notify(new VisitLogStatusNotification($visitLog));
            $visitor?->notify(new VisitLogStatusNotification($visitLog));
        } catch (\Exception $e) {
            Log::error('Notification failed: '.$e->getMessage());
        }

        toastr()->success("Visitor {$visitor->full_name}, Checkin successfully.");
        flash()->success("Now wait for Host Confirmation.");

        $this->reset([
            'visitor_id', 'host_id', 'purpose', 'visit_reason_detail', 'visitor_type_id',
            'appointment_id', 'badge_number', 'approval_status', 'rejection_reason',
            'registered_ip', 'device_name', 'remarks', 'location', 'status'
        ]);
    }

    public function render()
    {
        return view('livewire.visitor.visit-log-form', [
            'visitors' => Visitor::orderBy('full_name')->get(),
            'hosts' => User::orderBy('name')->get(),
            'visitorTypes' => VisitorType::all(),
            'appointments' => Appointment::all(),
        ])->layout('layouts.app');
    }

    
}
