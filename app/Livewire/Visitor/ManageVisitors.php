<?php

namespace App\Livewire\Visitor;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visitor;
use Livewire\Component;
use App\Models\VisitLog;
use App\Models\TransferLog;
use App\Models\VisitorType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageVisitors extends Component
{
    // --- Modal & Search
    public $selectedVisitor = null;
    public $searchTerm = '';
    public $searchResults = [];
    public $showCheckInModal = false;

    // --- Visitor / visit form fields
    public $visitor_id, $purpose, $visit_reason_detail, $host_id, $appointment_id;
    public $badge_number, $remarks, $location, $visitor_type_id;
    public $transferToHostId, $selectedVisitLog;

    // --- Statistics
    public $visitLogs = [];
    public $transferCount = 0;
    public $checkedInCount = 0;
    public $checkedOutCount = 0;
    public $waitingApprovalCount = 0;

    public $badgeNumberPrefix = 'VIS';
    public $defaultLocation = 'Main Lobby';
    public $hosts = [];
    public $visitorTypes = [];
public $showModal = false; // For Livewire 3 unified dispatch
public $isSearching = false;
public $showTransferModal = false;

//Add EventListener for Livewire 3 unified dispatch
    protected $listeners = ['showCheckinModal' => 'showCheckInModal'];

    public function mount()
    {
        $this->hosts = User::select('id', 'name', 'email')->get();
        $this->visitorTypes = VisitorType::all();
        $this->refreshStats();
        $this->loadRecentVisits();
        $this->badge_number = $this->generateBadgeNumber();

    }

    protected function refreshStats()
    {
        $this->checkedInCount = VisitLog::where('status', 'checked_in')->count();
        $this->checkedOutCount = VisitLog::where('status', 'checked_out')->count();
        $this->waitingApprovalCount = VisitLog::where('approval_status', 'pending')->count();
        $this->updateTransferCount();
    }

    public function updateTransferCount()
    {
        $this->transferCount = TransferLog::where('transferred_by', Auth::id())
            ->where('transferred_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    protected function loadRecentVisits()
    {
        $this->visitLogs = VisitLog::with('visitor', 'host', 'checkedOutBy')
            ->latest()->take(20)->get();
    }

    // --- Reactive search
public function updatedSearchTerm()
{
    $this->isSearching = true;

    if (strlen($this->searchTerm) >= 2) { // Only search after 2+ characters
        $this->searchResults = Visitor::where('phone', 'like', "%{$this->searchTerm}%")
            ->orWhere('id_number', 'like', "%{$this->searchTerm}%")
            ->take(10)
            ->get();
    } else {
        $this->searchResults = collect(); // Empty collection
    }

    $this->isSearching = false;
}


    // When a visitor is selected
public function selectVisitor($visitorId)
{
    $this->selectedVisitor = Visitor::find($visitorId);
    $this->visitor_id = $this->selectedVisitor->id;

    // Reset previous form fields but keep visitor_id
    $this->resetForm(false);

    // Auto-generate badge number
    $this->badge_number = $this->generateBadgeNumber();
    $this->location = $this->defaultLocation;
$this->showModal=true; // Show the modal
    // Dispatch the modal open event
    $this->dispatch('show-checkin-modal'); // Livewire 3 unified dispatch
}


    protected function generateBadgeNumber()
    {
        return DB::transaction(function () {
            $today = now()->format('Ymd');

            $lastBadge = VisitLog::whereDate('check_in_time', now())
                ->lockForUpdate()
                ->latest('id')
                ->value('badge_number');

            $number = ($lastBadge && preg_match('/(\d+)$/', $lastBadge, $matches))
                ? intval($matches[1]) + 1
                : 1;

            return sprintf('%s-%s-%03d', $this->badgeNumberPrefix, $today, $number);
        });
    }

    // --- Check-in
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
            'check_in_time' => now(),
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

        \Log::info('Visitor checked in', [
            'visit_log_id' => $visit->id,
            'visitor_id' => $this->visitor_id,
            'by' => Auth::id()
        ]);

        $this->resetForm();
        $this->dispatch('close-checkin-modal');
        $this->refreshStats();
        $this->loadRecentVisits();

        session()->flash('success', 'Visitor check-in initiated. Waiting for host approval.');
    }

    // --- Check-out
    public function checkOut($id)
    {
        $visit = VisitLog::findOrFail($id);

        if ($visit->status === 'checked_out') {
            session()->flash('error', 'Visitor already checked out.');
            return;
        }

        $visit->update([
            'checked_out_by' => Auth::id(),
            'check_out_time' => now(),
            'status' => 'checked_out',
        ]);

        $this->refreshStats();
        $this->loadRecentVisits();
        session()->flash('success', 'Visitor checked out successfully.');
    }

    // --- Visitor transfer
public function openTransferModal($visitLogId)
{
    $visitLog = VisitLog::findOrFail($visitLogId);

    if (!in_array($visitLog->status, ['pending', 'checked_in'])) {
        session()->flash('error', 'This visitor cannot be transferred.');
        return;
    }

    $this->selectedVisitLog = $visitLog;
    $this->dispatch('show-transfer-modal');
}

public function closeTransferModal()
{
    $this->reset([
        'selectedVisitLog',
        'transferToHostId', 
        'transferReason',
    ]);
    $this->dispatch('hide-transfer-modal');
}



public function transferVisitor()
{
    $this->validate([
        'transferToHostId' => 'required|exists:users,id',
    ]);

    if (!$this->selectedVisitLog) {
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'No visitor selected for transfer.'
        ]);
        return;
    }

    $fromHost = $this->selectedVisitLog->host_id;

    $this->selectedVisitLog->update([
        'host_id' => $this->transferToHostId,
        'transferred_by' => Auth::id(),
        'transferred_at' => now(),
    ]);

    TransferLog::create([
        'visitor_id' => $this->selectedVisitLog->visitor_id,
        'from_host' => $fromHost,
        'to_host' => $this->transferToHostId,
        'reason' => $this->transferReason ?? null,
        'transferred_at' => now(),
        'transferred_by' => Auth::id(),
        'created_by' => Auth::id(),
    ]);
    $this->transferToHostId = null;
    $this->selectedVisitLog = null;

    $this->reset([
        'selectedVisitLog',
        'transferToHostId',
        'transferReason',
    ]);

    $this->refreshStats();
    $this->loadRecentVisits();

    // Notify user & close modal
    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Visitor transferred successfully.'
    ]);
    $this->dispatch('closeModal', 'transferModal');
}

    // --- Reset forms
    public function resetForm($full = true)
    {
        if ($full) {
            $this->visitor_id = null;
            $this->selectedVisitor = null;
        }
        $this->host_id = null;
        $this->visitor_type_id = null;
        $this->purpose = '';
        $this->visit_reason_detail = '';
        $this->badge_number = '';
        $this->location = '';
        $this->appointment_id = null;
        $this->remarks = '';
    }

    public function render()
    {


        return view('livewire.visitor.manage-visitors', [
            'hostsList' => $this->hosts,
            'visitorTypes' => $this->visitorTypes,
            'visitLogs' => $this->visitLogs,
        ])->layout('components.layouts.visit');
    }
}
