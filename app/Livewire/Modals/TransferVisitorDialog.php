<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use App\Models\Visitor;
use App\Models\TransferLog;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransferVisitorDialog extends Component
{
    public $show = false;
    public $visitorId;
    public $visitor;
    public $newHost = '';
    public $reason = '';

    protected $listeners = ['showTransferDialog'];

    public function showTransferDialog($visitorId)
    {
        $this->visitorId = $visitorId;
        $this->visitor = Visitor::findOrFail($visitorId);
        $this->reset(['newHost', 'reason']);
        $this->show = true;
    }

    public function transfer()
    {
        $this->validate([
            'newHost' => 'required|string|max:255',
            'reason' => 'required|string|max:500',
        ]);

        // Prevent transferring to the same host
        if ($this->visitor->host === $this->newHost) {
            toastr()->error('New host must be different from the current host.');
            return;
        }

        // Save the transfer log
        TransferLog::create([
            'visitor_id' => $this->visitor->id,
            'from_host' => $this->visitor->host,
            'to_host' => $this->newHost,
            'reason' => $this->reason,
            'transferred_by' => Auth::id(),
        ]);

        // Update the visitor's host
        $this->visitor->update(['host' => $this->newHost]);

        // Add a record in visitor_logs for auditing
        VisitLog::create([
            'visitor_id' => $this->visitor->id,
            'action' => 'transfer',
            'performed_by' => Auth::id(),
            'timestamp' => now(),
            'details' => "Transferred from {$this->visitor->host} to {$this->newHost}. Reason: {$this->reason}",
        ]);

        Log::info("Visitor {$this->visitor->full_name} transferred from {$this->visitor->host} to {$this->newHost}");

        // Notify UI and close modal
        $this->dispatch('visitorUpdated');
        $this->show = false;
        toastr()->success('Visitor transferred successfully!');
    }

    public function render()
    {
        return view('livewire.modals.transfer-visitor-dialog');
    }
}
