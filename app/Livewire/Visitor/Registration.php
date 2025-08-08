<?php

namespace App\Livewire\Visitor;

use App\Models\Visitor;
use App\Models\VisitorType;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class Registration extends Component
{
    public $tab = 'single';
    public $visitorType = [];
    public $singleIdTypeOther = '';
    public $groupLeaderIdTypeOther = '';
    public $groupMembersIdTypeOther = [];
public $visitor = null;

    public function mount()
    {
        Log::info('Mounting Visitor Registration Component');

        $this->visitorType = VisitorType::pluck('name', 'id')->toArray();

if ($prefill = request('prefill')) {
        $this->single['phone'] = $prefill;
        $this->visitor = Visitor::where('phone', $prefill)
                            ->orWhere('id_number', $prefill)
                            ->orWhere('visitor_uid', $prefill)
                            ->first();
    }
    }

    // Single visitor form
    public $single = [
        'full_name' => '',
        'email' => '',
        'phone' => '',
        'gender' => '',
        'date_of_birth' => '',
        'nationality' => '',
        'address' => '',
        'company' => '',
        'id_type' => '',
        'id_number' => '',
        'city' => '',
        'visitor_type' => '',
    ];

    // Group visitor form
    public $group = [
        'leader' => [
            'full_name' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
            'date_of_birth' => '',
            'nationality' => '',
            'address' => '',
            'company' => '',
            'id_type' => '',
            'id_number' => '',
            'city' => '',
            'visitor_type' => '',
        ],
        'members' => [
            [
                'full_name' => '',
                'email' => '',
                'phone' => '',
                'gender' => '',
                'date_of_birth' => '',
                'nationality' => '',
                'address' => '',
                'company' => '',
                'id_type' => '',
                'id_number' => '',
                'city' => '',
                'visitor_type' => '',
            ]
        ],
    ];

    protected function rules()
    {
        Log::info("Applying validation rules for tab: {$this->tab}");

        if ($this->tab === 'single') {
            return [
                'single.full_name' => 'required|string|max:255',
                'single.email' => 'nullable|email|max:255',
                'single.phone' => 'nullable|string|max:50',
                'single.gender' => 'nullable|in:Male,Female,Other',
                'single.date_of_birth' => 'nullable|date',
                'single.nationality' => 'nullable|string|max:100',
                'single.address' => 'nullable|string|max:255',
                'single.company' => 'nullable|string|max:255',
                'single.id_type' => 'nullable|string|max:50',
                'single.id_number' => 'nullable|string|max:100',
                'single.city' => 'nullable|string|max:100',
                'single.visitor_type' => 'required|exists:visitor_types,id',
            ];
        }

        $rules = [
            'group.leader.full_name' => 'required|string|max:255',
            'group.leader.email' => 'nullable|email|max:255',
            'group.leader.phone' => 'nullable|string|max:50',
            'group.leader.gender' => 'nullable|in:Male,Female,Other',
            'group.leader.date_of_birth' => 'nullable|date',
            'group.leader.nationality' => 'nullable|string|max:100',
            'group.leader.address' => 'nullable|string|max:255',
            'group.leader.company' => 'nullable|string|max:255',
            'group.leader.id_type' => 'nullable|string|max:50',
            'group.leader.id_number' => 'nullable|string|max:100',
            'group.leader.city' => 'nullable|string|max:100',
        ];

        foreach ($this->group['members'] as $index => $member) {
            $rules["group.members.$index.full_name"] = 'required|string|max:255';
            $rules["group.members.$index.phone"] = 'nullable|string|max:50';
            $rules["group.members.$index.company"] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function addMember()
    {
        Log::info('Adding new group member');
        $this->group['members'][] = [
            'full_name' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
            'date_of_birth' => '',
            'nationality' => '',
            'address' => '',
            'company' => '',
            'id_type' => '',
            'id_number' => '',
            'city' => '',
            'visitor_type' => '',
        ];
    }

    public function removeMember($index)
    {
        Log::info("Removing group member at index: {$index}");
        unset($this->group['members'][$index]);
        $this->group['members'] = array_values($this->group['members']);
    }

  private function generateVisitorUID(): string
{
    $year = now()->year;

    // Get the latest visitor UID for the current year
    $lastVisitor = Visitor::whereYear('created_at', $year)
        ->where('visitor_uid', 'like', "WG-VTR-%-$year")
        ->orderByDesc('created_at')
        ->first();

    if ($lastVisitor && preg_match('/WG-VTR-(\d{4})-' . $year . '/', $lastVisitor->visitor_uid, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }

    $number = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    return "WG-VTR-{$number}-{$year}";
}


    // public function registerSingleVisitor()
    // {
    //     Log::info('Registering single visitor', ['data' => $this->single]);

    //     $this->validate();

    //     $idType = $this->single['id_type'] === 'Other' ? $this->singleIdTypeOther : $this->single['id_type'];

    //     Visitor::create(array_merge($this->single, [
    //         'id_type' => $idType,
    //         'visitor_uid' => $this->generateVisitorUID(),
    //         'visitor_type_id' => $this->single['visitor_type'],
    //         'created_by' => Auth::id(),
    //         'status' => 'active',
    //         'is_leader' => false,
    //         'group_uid' => null,
    //     ]));

    //     Log::info('Single visitor created successfully');

    //     session()->flash('message', 'Single visitor registered successfully.');

    //     // Reset form
    //     $this->reset('single', 'singleIdTypeOther');
    // }

    // public function registerGroupVisitors()
    // {
    //     Log::info('Registering group visitors', ['leader' => $this->group['leader'], 'members' => $this->group['members']]);

    //     $this->validate();

    //     $groupUid = (string) Str::uuid();

    //     $leaderIdType = $this->group['leader']['id_type'] === 'Other' ? $this->groupLeaderIdTypeOther : $this->group['leader']['id_type'];

    //     Visitor::create(array_merge($this->group['leader'], [
    //         'id_type' => $leaderIdType,
    //         'visitor_uid' => $this->generateVisitorUID(),
    //         'visitor_type_id' => $this->group['leader']['visitor_type'] ?? 1,
    //         'created_by' => Auth::id(),
    //         'status' => 'active',
    //         'is_leader' => true,
    //         'group_uid' => $groupUid,
    //     ]));

    //     foreach ($this->group['members'] as $index => $member) {
    //         $memberIdType = $member['id_type'] === 'Other' ? ($this->groupMembersIdTypeOther[$index] ?? 'Other') : $member['id_type'];

    //         Visitor::create(array_merge($member, [
    //             'id_type' => $memberIdType,
    //             'visitor_uid' => $this->generateVisitorUID(),
    //             'visitor_type_id' => $member['visitor_type'] ?? 1,
    //             'created_by' => Auth::id(),
    //             'status' => 'active',
    //             'is_leader' => false,
    //             'group_uid' => $groupUid,
    //         ]));

    //         Log::info("Group member #{$index} registered");
    //     }

    //     session()->flash('message', 'Group visitors registered successfully.');

    //     // Reset group form
    //     $this->reset('group', 'groupLeaderIdTypeOther', 'groupMembersIdTypeOther');
    // }


    public function registerSingleVisitor()
{
    $this->validate();

    $idType = $this->single['id_type'] === 'Other' ? $this->singleIdTypeOther : $this->single['id_type'];

    Visitor::create(array_merge($this->single, [
        'id_type' => $idType,
        'visitor_uid' => $this->generateVisitorUID(),
        'visitor_type_id' => $this->single['visitor_type'],
        'created_by' => Auth::id(),
        'status' => 'active',
        'is_leader' => false,
        'group_uid' => null,
    ]));

    $this->reset('single', 'singleIdTypeOther');

    // Dispatch browser event for toastr
    $this->dispatch('notify', ['type' => 'success', 'message' => 'Single visitor registered successfully.']);
        return redirect()->route('visitor.dashboard');
    Log::info('Single visitor registered successfully', ['visitor_uid' => $this->single['visitor_uid']]);
}

//Generate a unique group UID
// Format: WG-GRP-XXXXXX-YYYY (where XXXXXX is a random 6-character string and YYYY is the current year)
private function generateGroupUID(): string
{
    $year = now()->year;
    $random = strtoupper(Str::random(6));

    return "WG-GRP-{$random}-{$year}";
}


public function registerGroupVisitors()
{
    $this->validate();

   $groupUid = $this->generateGroupUID();

    $leaderIdType = $this->group['leader']['id_type'] === 'Other' ? $this->groupLeaderIdTypeOther : $this->group['leader']['id_type'];

    Visitor::create(array_merge($this->group['leader'], [
        'id_type' => $leaderIdType,
        'visitor_uid' => $this->generateVisitorUID(),
        'visitor_type_id' => $this->group['leader']['visitor_type'] ?? 1,
        'created_by' => Auth::id() ?? "",
        'status' => 'active',
        'is_leader' => true,
        'group_uid' => $groupUid,
    ]));

    foreach ($this->group['members'] as $index => $member) {
        $memberIdType = $member['id_type'] === 'Other' ? ($this->groupMembersIdTypeOther[$index] ?? 'Other') : $member['id_type'];

        Visitor::create(array_merge($member, [
            'id_type' => $memberIdType,
            'visitor_uid' => $this->generateVisitorUID(),
            'visitor_type_id' => $member['visitor_type'] ?? 1,
            'created_by' => Auth::id(),
            'status' => 'active',
            'is_leader' => false,
            'group_uid' => $groupUid,
        ]));
    }

    $this->reset('group', 'groupLeaderIdTypeOther', 'groupMembersIdTypeOther');

    // Dispatch browser event for toastr
    $this->dispatch('notify', ['type' => 'success', 'message' => 'Group visitors registered successfully.']);
        return redirect()->route('visitor.dashboard');
    Log::info('Group visitors registered successfully', ['group_uid' => $groupUid]);
}


    public function render()
    {
        return view('livewire.visitor.registration')->layout('components.layouts.visit');
    }
}
