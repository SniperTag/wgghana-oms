<?php

namespace App\Livewire\Visitor;

use App\Models\User;
use App\Models\Visitor;
use Livewire\Component;
use App\Models\VisitorType;
use Livewire\WithFileUploads;
use App\Services\VisitorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\VisitorRegistrationRequest;

class Registration extends Component
{
    use WithFileUploads;

    // Registration mode
    public bool $isTeam = false;

    // Single visitor properties
    public string $email = '';
    public string $full_name = '';
    public string $phone = '';
    public string $company = '';
    public string $id_type = '';
    public string $id_number = '';
    public $photo = null;
    public $signature = null;
    public string $status = 'active';
    public ?int $created_by = null;
    public string $gender = '';
    public string $date_of_birth = '';
    public string $nationality = '';
    public string $address = '';
    public string $city = '';
    public ?int $host_id = null;
    public ?string $visitor_type_id = null;

    // Team properties
    public array $teamVisitors = [];
    public ?int $leaderIndex = null;

    // Cached data
    public $visitorTypes;

    // Services
    protected VisitorService $visitorService;
    protected NotificationService $notificationService;


//  public function boot(VisitorService $visitorService, NotificationService $notificationService)
//  {
//      $this->visitorService = $visitorService;
//      $this->notificationService = $notificationService;
//  }
 
    public function switchToGroup()
    {
        $this->isTeam = true;
    }

    public function switchToSingle()
    {
        $this->isTeam = false;
    }



    protected function rules(): array
    {
        if ($this->isTeam) {
            return $this->getTeamValidationRules();
        }

        return $this->getSingleVisitorValidationRules();
    }

    protected function getSingleVisitorValidationRules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:visitors,email',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:visitors,phone',
            'company' => 'nullable|string|max:255',
            'status' => 'required|in:active,banned',
            'id_type' => 'required|string|max:50',
            'id_number' => 'required|string|max:50|unique:visitors,id_number',
            'photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            'signature' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'visitor_type_id' => 'nullable|exists:visitor_types,id',
            'city' => 'nullable|string|max:100',
            'host_id' => 'nullable|exists:users,id',
        ];
    }

    protected function getTeamValidationRules(): array
    {
        return [
            'teamVisitors' => 'required|array|min:1|max:10',
            'teamVisitors.*.full_name' => 'required|string|max:255',
            'teamVisitors.*.email' => 'required|email|max:255|distinct',
            'teamVisitors.*.phone' => 'nullable|string|max:20|distinct',
            'teamVisitors.*.company' => 'nullable|string|max:255',
            'teamVisitors.*.status' => 'required|in:active,banned',
            'teamVisitors.*.id_type' => 'required|string|max:50',
            'teamVisitors.*.id_number' => 'required|string|max:50|distinct',
            'teamVisitors.*.photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            'teamVisitors.*.signature' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            'teamVisitors.*.gender' => 'nullable|in:Male,Female,Other',
            'teamVisitors.*.date_of_birth' => 'nullable|date|before:today',
            'teamVisitors.*.nationality' => 'nullable|string|max:100',
            'teamVisitors.*.address' => 'nullable|string|max:500',
            'teamVisitors.*.visitor_type_id' => 'nullable|exists:visitor_types,id',
            'teamVisitors.*.city' => 'nullable|string|max:100',
            'teamVisitors.*.host_id' => 'nullable|exists:users,id',
            'teamVisitors.*.is_leader' => 'boolean',
            'leaderIndex' => 'required|integer|min:0'
        ];
    }

    public function mount(VisitorService $visitorService, NotificationService $notificationService): void
    {
        $this->visitorService = $visitorService;
        $this->notificationService = $notificationService;

        $this->created_by = Auth::id();
        $this->loadVisitorTypes();
        $this->initializeTeamVisitors();
    }

    protected function loadVisitorTypes(): void
    {
        $this->visitorTypes = Cache::remember('visitor_types', 3600, function () {
            return VisitorType::select('id', 'name')->get();
        });
    }


    protected function initializeTeamVisitors(): void
    {
        $this->teamVisitors = [$this->createEmptyVisitor()];
    }

    protected function createEmptyVisitor(): array
    {
        return [
            'email' => '',
            'full_name' => '',
            'phone' => '',
            'company' => '',
            'id_type' => '',
            'id_number' => '',
            'photo' => null,
            'signature' => null,
            'visitor_type_id' => '',
            'status' => 'active',
            'gender' => '',
            'date_of_birth' => '',
            'nationality' => '',
            'address' => '',
            'city' => '',
            'host_id' => null,
            'is_leader' => false,
        ];
    }

    public function addTeamVisitor(): void
    {
        if (count($this->teamVisitors) >= 10) {
            $this->addError('teamVisitors', 'Maximum 10 visitors allowed per group.');
            return;
        }

        $this->teamVisitors[] = $this->createEmptyVisitor();
    }

    public function removeTeamVisitor(int $index): void
    {
        if (count($this->teamVisitors) <= 1) {
            $this->addError('teamVisitors', 'At least one visitor is required.');
            return;
        }

        if ($this->leaderIndex === $index) {
            $this->leaderIndex = 0; // Reset leader to first visitor
        } elseif ($this->leaderIndex > $index) {
            $this->leaderIndex--; // Adjust leader index
        }

        unset($this->teamVisitors[$index]);
        $this->teamVisitors = array_values($this->teamVisitors);
        $this->updateLeaderFlags();
    }

    public function selectLeader(int $index): void
    {
        $this->leaderIndex = $index;
        $this->updateLeaderFlags();
    }

    protected function updateLeaderFlags(): void
    {
        foreach ($this->teamVisitors as $i => &$visitor) {
            $visitor['is_leader'] = ($i === $this->leaderIndex);
        }
    }

    public function updatedIsTeam(): void
    {
        $this->resetValidation();
        $this->resetForm();
    }

    public function resetForm(): void
    {
        if ($this->isTeam) {
            $this->initializeTeamVisitors();
            $this->leaderIndex = 0;
        } else {
            $this->reset([
                'email',
                'full_name',
                'phone',
                'company',
                'id_type',
                'id_number',
                'photo',
                'signature',
                'gender',
                'date_of_birth',
                'nationality',
                'address',
                'city',
                'host_id',
                'visitor_type_id'
            ]);
            $this->status = 'active';
        }
    }

    public function registerVisitor(): void
    {
        $this->resetValidation();

        try {
            $this->validate();

            DB::transaction(function () {
                if ($this->isTeam) {
                    $this->registerTeamVisitors();
                } else {
                    $this->registerSingleVisitor();
                }
            });

            $this->resetForm();

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Visitor registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $this->created_by
            ]);

            $this->addError('registration', 'Registration failed. Please try again.');
        }
    }

    protected function registerSingleVisitor(): void
    {
        $visitorData = $this->prepareSingleVisitorData();

        $visitor = $this->visitorService->createVisitor($visitorData);

        $this->notificationService->sendVisitorNotifications($visitor);

        session()->flash('success', 'Visitor registered successfully.');

        Log::info('Single visitor registered', ['visitor_id' => $visitor->id]);
    }

    protected function registerTeamVisitors(): void
    {
        if ($this->leaderIndex === null) {
            throw new \InvalidArgumentException('Group leader must be selected.');
        }

        $groupUid = $this->visitorService->generateGroupUid();
        $registeredVisitors = [];

        foreach ($this->teamVisitors as $index => $visitorData) {
            $visitorData['group_uid'] = $groupUid;
            $visitorData['is_leader'] = ($index === $this->leaderIndex);
            $visitorData['created_by'] = $this->created_by;

            $visitor = $this->visitorService->createVisitor($visitorData);
            $registeredVisitors[] = $visitor;
        }

        // Send notifications for all visitors
        foreach ($registeredVisitors as $visitor) {
            $this->notificationService->sendVisitorNotifications($visitor);
        }

        session()->flash('success', 'Group visitors registered successfully! Group leader assigned.');

        Log::info('Group visitors registered', [
            'group_uid' => $groupUid,
            'count' => count($registeredVisitors)
        ]);
    }

    protected function prepareSingleVisitorData(): array
    {
        return [
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'id_type' => $this->id_type,
            'id_number' => $this->id_number,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'visitor_type_id' => $this->visitor_type_id,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth ?: null,
            'nationality' => $this->nationality,
            'address' => $this->address,
            'city' => $this->city,
            'host_id' => $this->host_id,
            'photo' => $this->photo,
            'signature' => $this->signature,
        ];
    }

    protected function messages(): array
    {
        return [
            'teamVisitors.*.email.distinct' => 'Each team member must have a unique email address.',
            'teamVisitors.*.phone.distinct' => 'Each team member must have a unique phone number.',
            'teamVisitors.*.id_number.distinct' => 'Each team member must have a unique ID number.',
            'leaderIndex.required' => 'Please select a group leader.',
        ];
    }

    public function render()
    {
        return view('livewire.visitor.registration', [
            'visitorTypes' => $this->visitorTypes,
            'hosts' => $this->getHosts()
        ])->layout('layouts.partials.visitor');
    }

    protected function getHosts()
    {
        return Cache::remember('active_hosts', 1800, function () {
            return User::active()
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
        });
    }
}
