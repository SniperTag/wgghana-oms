<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use App\Models\Visitor;
use App\Models\VisitLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\VisitorType;
use App\Services\AppointmentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class VisitorsDashboard extends Component
{
    public $search = '';
    public $status = 'active'; // since only 'active' or 'banned'
    public array $expandedGroups = [];
    public array $groupSearchTerms = [];
    
    public $visitors;
    public $showModal = false;
    public $selectedVisitor = null;
    public $visitLogId = null;
    public $showHostApprovalModal = false;
    public $hostId;
    public $visitorReasonDetail = null;
    public $purpose = '';
    public $declineReason = '';
    public $badge_number = '';
    public $visitorToEdit;
    public $visitorToDelete;
    public $showEditModal = false;
    public $showDeleteConfirm = false;
    // protected $listeners = ['showBadge'];
    public $selectedVisitorId = null;
    public $view = 'today'; // 'today' or 'upcoming'

// Dependency injection for AppointmentService


  public function mount()
    {
        $this->visitors = Visitor::all();
        $this->status = 'active';

    }

    // Render method to fetch visitors and appointments
      public function switchTo($view)
    {
        $this->view = $view;
    }

     public function showBadge($visitorId)
    {
        $this->loadVisitor($visitorId);

        // Dispatch event to trigger modal on frontend
        $this->dispatchBrowserEvent('show-badge-modal');

        Log::info('Visitor badge modal opened', ['visitor_id' => $visitorId]);
    }

    public function toggleGroup($index)
{
    if (in_array($index, $this->expandedGroups)) {
        $this->expandedGroups = array_diff($this->expandedGroups, [$index]);
    } else {
        $this->expandedGroups[] = $index;
    }
}
public function exportGroup($groupUid)
{
    $members = Visitor::where('group_uid', $groupUid)->get();
    $csv = "Full Name,Email,Phone,Company,Status\n";

    foreach ($members as $m) {
        $csv .= "{$m->full_name},{$m->email},{$m->phone},{$m->company},{$m->status}\n";
    }

    return response()->streamDownload(function () use ($csv) {
        echo $csv;
    }, "group_{$groupUid}_visitors.csv");
}


public function closeModal()
{
    $this->showModal = false;
    $this->selectedVisitor = null;
}

public function showVisitorDetails($visitorId)
{
    $this->selectedVisitor = Visitor::find($visitorId);
    $this->showModal = true;
}

// Request check-in: creates a VisitLog with 'pending' status

public function requestCheckIn($visitorId)
{
    // Optional: If host is selected via dropdown, you already have $this->hostId
    $host = User::find($this->hostId);

    if (!$host || !$host->hasRole('host')) {
        session()->flash('error', 'Selected user is not a valid host.');
        return;
    }

    // Check if there's an existing visit log (pending or active)
    $existing = VisitLog::where('visitor_id', $visitorId)
        ->whereIn('status', ['pending', 'checked_in'])
        ->first();

    if ($existing) {
        session()->flash('error', 'Visitor already has an active or pending visit.');
        return;
    }

    // Get visitor type id (e.g., Walk-in)
    $visitorType = VisitorType::where('name', 'Walk-in')->first();

    // Create visit log
    $visitLog = VisitLog::create([
        'visitor_id' => $visitorId,
        'host_id' => $this->hostId,
        'status' => 'pending',
        'approval_status' => 'pending',
        'check_in_time' => null,
        'check_out_time' => null,
        'purpose' => $this->purpose,
        'visit_reason_detail' => $this->visitReasonDetail ?? null,
        'badge_number' => strtoupper(Str::random(4)),
        'remarks' => null,
        'location' => null,
        'checked_in_by' => Auth::id(),
        'checked_out_by' => null,
        'device_ip' => request()->ip(),
        'device_name' => request()->header('User-Agent'),
        'visitor_type_id' => $visitorType?->id,
        'rejection_reason' => null,
    ]);

    // Store log ID if needed
    $this->visitLogId = $visitLog->id;

    // Notify host (you can use notification/event later)
    $this->dispatchBrowserEvent('host-visit-approval', [
        'visitLogId' => $visitLog->id,
        'hostId' => $this->hostId,
    ]);

    session()->flash('message', 'Check-in request sent to host.');
    $this->showModal = false;
}


// Host approves check-in
public function approveCheckIn()
{
    $visitLog = VisitLog::find($this->visitLogId);
    if (!$visitLog) {
        session()->flash('error', 'Visit log not found.');
        return;
    }

    $visitLog->update([
        'status' => 'checked_in',
        'approval_status' => 'approved',
        'check_in_time' => Carbon::now(),
        'checked_in_by' => Auth::id(), // or host id
    ]);

    $this->showHostApprovalModal = false;
    session()->flash('message', 'Check-in approved.');
    $this->reset('visitLogId', 'declineReason');
}



// Host declines check-in
public function declineCheckIn()
{
    if (empty($this->declineReason)) {
        $this->addError('declineReason', 'Please provide a reason for declining.');
        return;
    }

    $visitLog = VisitLog::find($this->visitLogId);
    if (!$visitLog) {
        session()->flash('error', 'Visit log not found.');
        return;
    }

    $visitLog->update([
        'status' => 'cancelled',
        'approval_status' => 'rejected',
        'rejection_reason' => $this->declineReason,
    ]);

    $this->showHostApprovalModal = false;
    session()->flash('message', 'Check-in declined.');
    $this->reset('visitLogId', 'declineReason');
}

// Visitor check-out
public function checkOut($visitLogId)
{
    $visitLog = VisitLog::find($visitLogId);
    if (!$visitLog || $visitLog->status !== 'checked_in') {
        session()->flash('error', 'No active check-in found.');
        return;
    }

    $visitLog->update([
        'check_out_time' => Carbon::now(),
        'status' => 'checked_out',
        'checked_out_by' => Auth::id(),
    ]);

    session()->flash('message', 'Visitor checked out successfully.');
}




public function render()
{
    $status = in_array($this->status, ['active', 'banned']) ? $this->status : 'active';

    $individualVisitors = Visitor::where('status', $status)
        ->whereNull('group_uid')
        ->when($this->search, function($query) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10); // paginate(10)

    $groups = Visitor::select('group_uid')
        ->whereNotNull('group_uid')
        ->where('status', $status)
        ->distinct()
        ->pluck('group_uid');

    $groupVisitors = collect();

    foreach ($groups as $groupUid) {
        $members = Visitor::where('group_uid', $groupUid)
            ->where('status', $status)
            ->get();

        $leader = $members->firstWhere('is_leader', true);
        if (!$leader) continue;

        $filteredMembers = $members;

        if (!empty($this->groupSearchTerms[$groupUid])) {
            $search = strtolower($this->groupSearchTerms[$groupUid]);
            $filteredMembers = $members->filter(function ($member) use ($search) {
                return str_contains(strtolower($member->full_name), $search) ||
                       str_contains(strtolower($member->company), $search);
            });
        }

        $groupVisitors->push([
            'group_uid' => $groupUid,
            'leader' => $leader,
            'members_count' => $members->count(),
            'members' => $filteredMembers->values()->all(),
        ]);
    }

    $summary = [
        'individual_count' => $individualVisitors->count(),
        'group_count' => $groupVisitors->count(),
        'total_visitors' => $individualVisitors->count() + $groupVisitors->sum('members_count'),
    ];

    $appointments = app(AppointmentService::class);

    return view('livewire.visitor.visitors-dashboard', [
        'individualVisitors' => $individualVisitors,
        'groupVisitors' => $groupVisitors,
        'summary' => $summary,
        'todayAppointments' => $appointments->getTodayAppointments(),
        'upcomingAppointments' => $appointments->getUpcomingAppointments(),
    ])->layout('components.layouts.visit');
}


}
