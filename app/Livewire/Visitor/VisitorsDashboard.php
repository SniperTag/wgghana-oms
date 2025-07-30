<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Visitor;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\VisitLog;

class VisitorsDashboard extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = 'all';
    public $visitorCount = 0;
    public $pendingVisitors = 0;
    public $checkedInVisitors = 0;
    public $checkedOutVisitors = 0;
    public $showModal = false;
    public $selectedVisitor;
    public $transferHost;
    public $transferReason;

    public function render()
    {
        // ✅ Fetch only leaders or single visitors
        $visitors = Visitor::where(function ($query) {
                $query->whereNull('group_uid') // single visitors
                      ->orWhere('is_leader', true); // leaders
            })
            ->with('group_members') // load members
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('company', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        // ✅ Dashboard metrics
        $this->visitorCount = Visitor::count();
        $this->pendingVisitors = VisitLog::where('status', 'pending')->count();
        $this->checkedInVisitors = VisitLog::where('status', 'checked-in')->count();
        $this->checkedOutVisitors = VisitLog::where('status', 'checked-out')->count();

        // ✅ Today's appointments
        $todayAppointments = Appointment::with(['visitors', 'user'])
            ->whereDate('scheduled_at', Carbon::today())
            ->orderBy('scheduled_at')
            ->get();

        return view('livewire.visitor.visitors-dashboard', compact('visitors', 'todayAppointments'))
            ->layout('layouts.visit');
    }

    public function viewDetails($id)
    {
        $this->selectedVisitor = Visitor::find($id);
        $this->dispatch('showVisitorModal');

        if (!$this->selectedVisitor) {
            session()->flash('error', 'Visitor not found.');
            return;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedVisitor = null;
        $this->dispatch('hideVisitorModal');
    }

    public function checkOut($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['status' => 'checked-out', 'check_out_time' => now()]);
        session()->flash('message', 'Visitor has been checked out.');
    }
}
