<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StepOut;
use App\Models\BreakSession;
use Livewire\WithPagination;

class StepOutReport extends Component
{
    use WithPagination;

    public $filter = 'this_week';

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $stepOutQuery = StepOut::with('user');
        $breakQuery = BreakSession::with('user');

        if ($this->filter === 'this_week') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();

            $stepOutQuery->whereBetween('stepped_out_at', [$start, $end]);
            $breakQuery->whereBetween('started_at', [$start, $end]);
        } elseif ($this->filter === 'this_month') {
            $month = now()->month;

            $stepOutQuery->whereMonth('stepped_out_at', $month);
            $breakQuery->whereMonth('started_at', $month);
        }

        $stepOutRecords = $stepOutQuery->orderByDesc('stepped_out_at')->paginate(10, ['*'], 'stepoutPage');
        $breakRecords = $breakQuery->orderByDesc('started_at')->paginate(10, ['*'], 'breakPage');

        return view('livewire.step-out-report', [
            'stepOutRecords' => $stepOutRecords,
            'breakRecords' => $breakRecords,
        ]);
    }
}
