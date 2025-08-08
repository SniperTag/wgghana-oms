<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use App\Models\User;
use App\Models\Visitor;
use App\Models\Department;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitorType;

class BookAppointment extends Component
{
    // Step control
    public $step = 1;

    // Step 1: Pre-screening input
    public $search_input;

    // Visitor details
    public $visitor_id;
    public $visitor_name, $visitor_email, $visitor_phone;

    // Appointment details
    public $department_id, $host_id, $title, $description;
    public $meeting_type = 'Physical';
    public $location;
    public $date, $time;
    // Dropdown options
    public $visitorType;
    public $visitorTypeSearch = '';
    public $visitor_type_id = null;

    public $departments = [], $hosts = [];

    public function mount()
    {
        $this->departments = Department::all();
        $this->hosts = User::all();
        $this->visitorType = VisitorType::all();
    }

    /**
     * Step 1: Search visitor by phone / ID / UID
     */
    public function searchVisitor()
    {
        $this->validate([
            'search_input' => 'required|string|max:50',
        ]);

        $visitor = Visitor::where('phone', $this->search_input)
            ->orWhere('id_number', $this->search_input)
            ->orWhere('visitor_uid', $this->search_input)
            ->first();

        if ($visitor) {
            // Autofill visitor info
            $this->visitor_id = $visitor->id;
            $this->visitor_name = $visitor->full_name;
            $this->visitor_email = $visitor->email;
            $this->visitor_phone = $visitor->phone;
            $this->step = 2;
        } else {
            session()->flash('error', 'Sorry. Please register first.');
            return redirect()->to(route('visitor.registration', ['prefill' => $this->search_input]));
        }
    }

    public function rules()
    {
        return [
            'visitor_name' => 'required|string|max:255',
            'visitor_email' => 'required|email|max:255',
            'visitor_phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'host_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'meeting_type' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
        ];
    }

    /**
     * Step 2: Submit appointment
     */
    public function submit()
    {
        \Log::info('Submitting Method Called');

        $this->validate();

        try {
            Appointment::create([
                'visitor_id'     => $this->visitor_id,
                'visitor_name'   => $this->visitor_name,
                'visitor_email'  => $this->visitor_email,
                'visitor_phone'  => $this->visitor_phone,
                'department_id'  => $this->department_id,
                'host_id'        => $this->host_id,
                'title'          => $this->title,
                'description'    => $this->description,
                'date'           => $this->date,
                'time'           => $this->time,
                'meeting_type'   => $this->meeting_type,
                'location'       => $this->location,
                'status'         => 'pending',
                'created_by'     => Auth::id(),
            ]);
            \Log::info('Validation Passed');

            session()->flash('message', 'Appointment booked successfully!');
            return redirect()->route('visitor.dashboard'); // or wherever you list appointments

        } catch (\Exception $e) {
            Log::error('Appointment booking failed: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong while booking the appointment.');
        }
    }

   public function render()
{
    $filteredTypes = collect();

    if (strlen($this->visitorTypeSearch) > 1) {
        $filteredTypes = $this->visitorType->filter(function ($type) {
            return str_contains(strtolower($type->name), strtolower($this->visitorTypeSearch));
        });
    }

    return view('livewire.visitor.book-appointment', [
        'filteredVisitorTypes' => $filteredTypes,
    ])->layout('components.layouts.visit');
}

}
