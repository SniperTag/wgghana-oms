<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\Visitor;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VisitorDetails extends Component
{
    public $visitorId;
    public $visitor = null;
    public $showModal = false;

    protected $listeners = ['showVisitorDetails'];

public function showVisitorDetails($payload)
{
    $id = $payload['id'];
    $this->visitor = Visitor::with('transfers')->find($id);
    $this->showModal = true;
}

    public function checkIn()
    {
        if (!$this->visitor) return;

        $this->visitor->update([
            'status' => 'checked-in',
            'check_in_time' => now(),
        ]);

        // Log check-in
        VisitorLog::create([
            'visitor_id' => $this->visitor->id,
            'action' => 'check-in',
            'performed_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        Log::info("Visitor {$this->visitor->full_name} checked in.");
        $this->emit('visitorUpdated');
        $this->showModal = false;
        session()->flash('message', 'Visitor checked in successfully.');
    }

    public function checkOut()
    {
        if (!$this->visitor) return;

        $this->visitor->update([
            'status' => 'checked-out',
            'check_out_time' => now(),
        ]);

        VisitorLog::create([
            'visitor_id' => $this->visitor->id,
            'action' => 'check-out',
            'performed_by' => Auth::id(),
            'timestamp' => now(),
        ]);

        Log::info("Visitor {$this->visitor->full_name} checked out.");
        $this->emit('visitorUpdated');
        $this->showModal = false;
        session()->flash('message', 'Visitor checked out successfully.');
    }

    public function transfer()
    {
        if (!$this->visitor) return;

        $this->emit('openTransferModal', $this->visitor->id);
        $this->showModal = false;
    }

    public function editVisitor()
    {
        if (!$this->visitor) return;

        $this->emit('openVisitorEditModal', $this->visitor->id);
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.modals.visitor-details');
    }
}
