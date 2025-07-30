<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BreakSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\BreakStatusUpdated;
use Carbon\Carbon;

class BreakManager extends Component
{

    use WithPagination;
protected $paginationTheme = 'bootstrap';
    public $onBreak = false;             // True if user currently on break
    public $breakId;                    // ID of current break session
    public $currentBreak;               // Current BreakSession model instance
    public $todayBreaks = [];           // Collection of today’s break sessions

    public $breakTypes = ['Lunch', 'Prayer', 'Rest','Breakfast','On Errand', 'Other']; // Available break types
    public $selectedBreakType = null;   // Break type selected by user

    // Max break duration allowed in minutes (e.g., 60 mins)
    public $maxBreakDuration = 60;

    /**
     * Initialize component and load break status on mount.
     */
    public function mount()
    {
        $this->loadBreakHistory();
    }

    /**
     * Loads current break session and today's break sessions.
     * Updates component state properties accordingly.
     */
    public function loadBreakHistory()
    {
        $user = Auth::user();

        // Fetch latest active break session (ended_at is null)
        $this->currentBreak = BreakSession::where('user_id', $user->id)
            ->whereNull('ended_at')
            ->latest()
            ->first();

        $this->onBreak = $this->currentBreak !== null;
        $this->breakId = $this->currentBreak?->id;

        Log::info('Loaded break status', [
            'user_id' => $user->id,
            'on_break' => $this->onBreak,
            'break_id' => $this->breakId,
        ]);

        // If currently on break, check for max duration warning
        if ($this->onBreak) {
            $this->checkBreakDuration();
        }

        // Load all breaks from today for display/history
        $this->todayBreaks = BreakSession::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->get();
    }

    /**
     * Start a break session after verifying break type selection.
     * Creates new BreakSession record and dispatches success toast.
     */
    public function startBreak()
    {
        if (!$this->selectedBreakType) {
            $this->dispatch('toast', title: 'Please select a break type', type: 'error');
            Log::warning('User tried to start break without selecting type', ['user_id' => Auth::id()]);
            return;
        }

        BreakSession::create([
            'user_id' => Auth::id(),
            'started_at' => now(),
            'break_type' => $this->selectedBreakType,
        ]);

        Log::info('Break started', [
            'user_id' => Auth::id(),
            'break_type' => $this->selectedBreakType,
            'started_at' => now()->toDateTimeString(),
        ]);

        $this->dispatch('toast', title: 'Break Started: ' . $this->selectedBreakType, type: 'success');

        $this->loadBreakHistory();
    }

    /**
     * Ends the current break session by updating 'ended_at'.
     * Dispatches info toast and logs the event.
     */
    public function endBreak()
    {
        $break = BreakSession::find($this->breakId);

        if (!$break) {
            Log::error('Attempted to end non-existent break session', ['break_id' => $this->breakId, 'user_id' => Auth::id()]);
            $this->dispatch('toast', title: 'Error ending break. Please try again.', type: 'error');
            return;
        }

        $break->update(['ended_at' => now()]);

        Log::info('Break ended', [
            'user_id' => Auth::id(),
            'break_id' => $this->breakId,
            'ended_at' => now()->toDateTimeString(),
        ]);

        $this->dispatch('toast', title: 'Break Ended', type: 'info');

        $this->loadBreakHistory();
    }

    /**
     * Checks if current break duration exceeds the maximum allowed.
     * Dispatches warning toast and logs a warning if exceeded.
     */
    public function checkBreakDuration()
    {
        if (!$this->currentBreak) {
            return; // No current break to check
        }

        $duration = Carbon::parse($this->currentBreak->started_at)->diffInMinutes(now());

        Log::info('Checking break duration', [
            'user_id' => Auth::id(),
            'break_id' => $this->breakId,
            'duration_minutes' => $duration,
        ]);

        if ($duration > $this->maxBreakDuration) {
            $this->dispatch('toast', title: 'Break exceeded max duration!', type: 'warning');

            Log::warning('Break duration exceeded max limit', [
                'user_id' => Auth::id(),
                'break_id' => $this->breakId,
                'duration_minutes' => $duration,
            ]);
        }
    }

    /**
     * Livewire hook triggered when $onBreak property updates.
     * Used here to check break duration if user is on break.
     */
    public function updatedOnBreak()
    {
        if ($this->onBreak) {
            $this->checkBreakDuration();
        }
    }

    public function toggleBreakHistory()
{
    $user = Auth::user();
    $user->on_break = !$user->on_break; // Add this boolean column if not existing
    $user->save();

    broadcast(new BreakStatusUpdated($user, $user->on_break))->toOthers();

    return response()->json(['status' => 'success']);
}

    /**
     * Render the Livewire view for break management.
     */
    public function render()
    {
        return view('livewire.break-manager', compact('todayBreaks'));

    }
}
