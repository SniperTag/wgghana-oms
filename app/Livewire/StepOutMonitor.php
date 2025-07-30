<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StepOut;
use App\Models\BreakSession;

class StepOutMonitor extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $filter = 'this_week';

    public function render()
    {
        $steppedOutStaff = StepOut::with('user')
            ->whereNull('returned_at')
            ->paginate(5);

        $breakSessions = BreakSession::with('user')
            ->whereNull('ended_at')
            ->paginate(5);

        return view('livewire.step-out-monitor', [
            'steppedOutStaff' => $steppedOutStaff,
            'breakSessions' => $breakSessions,
            'totalSteppedOut' => $steppedOutStaff->total(),
            'totalOnBreak' => $breakSessions->total(),
        ]);
    }

    public function updatedFilter()
{
    $this->resetPage(); // Reset pagination on filter change
}

public function getRecordsProperty()
{
    $query = StepOut::with('user')->orderBy('stepped_out_at', 'desc');

    if ($this->filter === 'this_week') {
        $query->whereBetween('stepped_out_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($this->filter === 'this_month') {
        $query->whereMonth('stepped_out_at', now()->month);
    }

    return $query->paginate(25);
}
}
