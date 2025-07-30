<?php

namespace App\Livewire;


use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ViewClockOutReason extends Component
{
    
    public $showModal = false;
    public $notes = '';
    public $attendanceRecordId;

    protected $listeners = ['showClockOutReasonModal' => 'loadReason'];

    public function loadReason($attendanceId)

    {
        Log::info("Show modal for ID: " . $attendanceId);
        $attendance = AttendanceRecord::find($attendanceId);

        $this->notes = $attendance && $attendance->notes
            ? $attendance->notes
            : 'No reason provided.';

        $this->attendanceRecordId = $attendanceId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

  
    public function render()
    {
        return view('livewire.view-clock-out-reason');
    }
}
