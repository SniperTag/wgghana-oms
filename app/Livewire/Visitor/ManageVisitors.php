<?php

namespace App\Livewire\Visitor;

use App\Models\Visitor;
use Livewire\Component;
use App\Models\VisitLog;
use App\Models\VisitorType;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ManageVisitors extends Component
{
    // --- Search input & modal control
    public $searchTerm;               // phone or id number typed by receptionist
    public $showCheckInModal = false; // toggles modal on frontend

    // --- Visitor / visit form fields
    public $selectedVisitor = null;   // Visitor model instance when found
    public $visitor_id, $purpose, $visit_reason_detail, $host_id, $appointment_id, $hosts;
    public $badge_number, $remarks, $location, $visitor_type_id;
    public $transferToHostId, $selectedVisitLog;
    public $visitLogs = [];

    // simple UX messages
    public $searchMessage = null;

    public function mount()
    {
        // preload small bundles — consider pagination/lazy-loading for large orgs
        $this->hosts = User::select('id','name','email')->get();
        $this->visitLogs = VisitLog::with('visitor', 'host', 'checkedOutBy')->latest()->take(20)->get();
    }

    /**
     * Search for a visitor by phone number OR id_number.
     * Called when receptionist searches.
     */
public function searchVisitor()
{
    $this->resetSearchState();

    $term = trim($this->searchTerm);
    if (empty($term)) {
        $this->searchMessage = 'Enter phone number or ID number to search.';
        return;
    }

    $visitor = Visitor::where('phone', $term)
                ->orWhere('id_number', $term)
                ->first();

    if (! $visitor) {
        $this->searchMessage = "No visitor found for \"{$term}\". You can register them first.";
        return;
    }

    // Just set the visitor details — don't open modal yet
    $this->selectedVisitor = $visitor;
    $this->visitor_id = $visitor->id;
    $this->badge_number = $visitor->badge_number ?? null;
    $this->location = $visitor->default_location ?? null;

    // Message to indicate visitor found
    $this->searchMessage = "Visitor found: {$visitor->name}";
}



public function clearSearch()
{
    $this->reset('searchTerm', 'searchMessage', 'selectedVisitor');
}



    public function openCheckInModal()
{
    $this->dispatch('open-checkin-modal');
}

    /**
     * Main check-in action (uses same logic as before but with stronger validation)
     */
    public function checkIn()
    {
        $this->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'purpose' => 'required|string|max:255',
            'visit_reason_detail' => 'nullable|string|max:1000',
            'host_id' => 'required|exists:users,id',
            'visitor_type_id' => 'required|exists:visitor_types,id',
            'badge_number' => 'required|string|max:50',
            'location' => 'required|string|max:255',
        ]);

        $visit = VisitLog::create([
            'visitor_id' => $this->visitor_id,
            'appointment_id' => $this->appointment_id,
            'purpose' => $this->purpose,
            'visit_reason_detail' => $this->visit_reason_detail,
            'host_id' => $this->host_id,
            'checked_in_by' => Auth::id(),
            'status' => 'pending',
            'approval_status' => 'pending',
            'badge_number' => $this->badge_number,
            'remarks' => $this->remarks,
            'location' => $this->location,
            'visitor_type_id' => $this->visitor_type_id,
            'device_ip' => request()->ip(),
            'device_name' => request()->header('User-Agent'),
        ]);

        // Log activity (simple)
        \Log::info('Visitor checked in', [
            'visit_log_id' => $visit->id,
            'visitor_id' => $this->visitor_id,
            'by' => Auth::id()
        ]);

        // Reset form & UI
        $this->resetForm();
        $this->showCheckInModal = false;
        $this->dispatch('close-checkin-modal');

        // refresh recent logs
        $this->visitLogs = VisitLog::with('visitor', 'host', 'checkedOutBy')->latest()->take(20)->get();

        session()->flash('success', 'Visitor check-in initiated. Waiting for host approval.');
    }

    /**
     * Reset only search-related messages & selectedVisitor (not whole form)
     */
    protected function resetSearchState()
    {
        $this->searchMessage = null;
        $this->selectedVisitor = null;
        $this->visitor_id = null;
        $this->showCheckInModal = false;
    }

    public function checkOut($id)
    {
        $visit = VisitLog::findOrFail($id);

        // safety: ensure it is currently checked in / pending
        if ($visit->status === 'checked_out') {
            session()->flash('error', 'Visitor already checked out.');
            return;
        }

        $visit->update([
            'checked_out_by' => Auth::id(),
            'checked_out_at' => now(),
            'status' => 'checked_out',
        ]);

        $this->visitLogs = VisitLog::with('visitor', 'host', 'checkedOutBy')->latest()->take(20)->get();
        session()->flash('success', 'Visitor checked out successfully.');
    }

    public function showTransferModal($id)
    {
        $this->selectedVisitLog = VisitLog::findOrFail($id);
    }

    public function transferVisitor()
    {
        $this->validate([
            'transferToHostId' => 'required|exists:users,id',
        ]);

        $this->selectedVisitLog->update([
            'host_id' => $this->transferToHostId,
            'transferred_by' => Auth::id(),
            'transferred_at' => now(),
        ]);

        $this->transferToHostId = null;
        $this->selectedVisitLog = null;
        $this->visitLogs = VisitLog::with('visitor', 'host', 'checkedOutBy')->latest()->take(20)->get();
        session()->flash('success', 'Visitor transferred successfully.');
    }

    public function resetForm()
    {
        $this->visitor_id = null;
        $this->purpose = null;
        $this->visit_reason_detail = null;
        $this->host_id = null;
        $this->appointment_id = null;
        $this->badge_number = null;
        $this->remarks = null;
        $this->location = null;
        $this->visitor_type_id = null;
    }

    public function render()
    {
        return view('livewire.visitor.manage-visitors', [
            'visitorTypes' => VisitorType::all(),
            'hostsList' => $this->hosts,
            'visitLogs' => $this->visitLogs,
        ])->layout('components.layouts.visit');
    }
}
