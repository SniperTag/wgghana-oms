<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;

class VisitorBadge extends Component
{
    public $visitor;

    protected $listeners = ['showBadge'];

    public function showBadge($id)
    {
        Log::info('showBadge called with ID: ' . $id);

        $this->visitor = Visitor::with('visitorType')->find($id);

        if (!$this->visitor) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Visitor not found.']);
            return;
        }

        $this->dispatchBrowserEvent('show-badge-modal');

        Log::info('Visitor badge modal opened', ['visitor_id' => $id]);
    }

    public function render()
    {
        return view('livewire.visitor.visitor-badge');
    }
}

