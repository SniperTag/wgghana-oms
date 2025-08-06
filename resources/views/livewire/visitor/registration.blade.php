<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Container -->
    <div>
        <main id="main-container" class="content-full">
            <div class="page-container d-flex flex-column min-vh-100">


                <div class="p-6 bg-white rounded shadow max-w-7xl mx-auto">
                    <h2 class="text-2xl font-bold mb-6">Visitor Registration</h2>

                    {{-- Toastr Notifications --}}
                    @if (session()->has('message'))
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                toastr.success("{{ session('message') }}");
                            });
                        </script>
                    @endif

                    @if ($errors->any())
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                toastr.error('Please fix the errors in the form and try again.');
                            });
                        </script>
                    @endif

                    <!-- Tabs -->
                    <div class="flex border-b mb-4 space-x-4">
                        <button wire:click="$set('tab', 'single')"
                            class="px-4 py-2 border-b-2 font-semibold {{ $tab === 'single' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500' }}">
                            Single Visitor
                        </button>
                        <button wire:click="$set('tab', 'group')"
                            class="px-4 py-2 border-b-2 font-semibold {{ $tab === 'group' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500' }}">
                            Group Visitors
                        </button>
                    </div>

                    {{-- Single Visitor Registration --}}
                    @if ($tab === 'single')
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <x-input label="Full Name" wire:model.defer="single.full_name" />
                                <x-input label="Email" wire:model.defer="single.email" />
                                <x-input label="Phone" wire:model.defer="single.phone" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                                    <select wire:model.defer="single.gender"
                                        class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                        <option value="">Select Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <x-input label="Date of Birth" type="date" wire:model.defer="single.date_of_birth" />
                                <x-input label="Nationality" wire:model.defer="single.nationality" />
                                <x-input label="City" wire:model.defer="single.city" />
                                <x-input label="Address" wire:model.defer="single.address" />
                                <x-input label="Company" wire:model.defer="single.company" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ID Type</label>
                                    <select wire:model="single.id_type"
                                        class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                        <option value="">Select ID Type</option>
                                        <option value="Ghana Card">Ghana Card</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Voter ID">Voter ID</option>
                                        <option value="Student ID">Student ID</option>
                                        <option value="Work ID">Work ID</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                @if ($single['id_type'] === 'Other')
                                    <x-input label="Specify ID Type" wire:model.defer="singleIdTypeOther" />
                                @endif

                                <x-input label="ID Number" wire:model.defer="single.id_number" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Visitor Type</label>
                                    <select wire:model.defer="single.visitor_type"
                                        class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                        <option value="">Select Visit Type</option>
                                        @foreach ($visitorType as $id => $label)
                                            <option value="{{ $id }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button wire:click="registerSingleVisitor"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Register Visitor
                            </button>
                        </div>
                    @endif

                    {{-- Group Visitor Registration --}}
                    @if ($tab === 'group')
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold">Group Leader</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                                <x-input label="Full Name" wire:model.defer="group.leader.full_name" />
                                <x-input label="Email" wire:model.defer="group.leader.email" />
                                <x-input label="Phone" wire:model.defer="group.leader.phone" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                                    <select wire:model.defer="group.leader.gender"
                                        class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                        <option value="">Select Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <x-input label="Date of Birth" type="date"
                                    wire:model.defer="group.leader.date_of_birth" />
                                <x-input label="Nationality" wire:model.defer="group.leader.nationality" />
                                <x-input label="City" wire:model.defer="group.leader.city" />
                                <x-input label="Address" wire:model.defer="group.leader.address" />
                                <x-input label="Company" wire:model.defer="group.leader.company" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ID Type</label>
                                    <select wire:model="group.leader.id_type"
                                        class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                        <option value="">Select ID Type</option>
                                        <option value="Ghana Card">Ghana Card</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Voter ID">Voter ID</option>
                                        <option value="Student ID">Student ID</option>
                                        <option value="Work ID">Work ID</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                @if ($group['leader']['id_type'] === 'Other')
                                    <x-input label="Specify ID Type" wire:model.defer="groupLeaderIdTypeOther" />
                                @endif

                                <x-input label="ID Number" wire:model.defer="group.leader.id_number" />

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Visitor Type</label>
                                    <select wire:model.defer="group.leader.visitor_type"
                                        class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                        <option value="">Select Visit Type</option>
                                        @foreach ($visitorType as $id => $label)
                                            <option value="{{ $id }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold mb-2">Group Members</h3>
                            @foreach ($group['members'] as $index => $member)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2 bg-gray-50 p-3 rounded">
                                    <x-input label="Full Name"
                                        wire:model.defer="group.members.{{ $index }}.full_name" />
                                    <x-input label="Email"
                                        wire:model.defer="group.members.{{ $index }}.email" />
                                    <x-input label="Phone"
                                        wire:model.defer="group.members.{{ $index }}.phone" />

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                                        <select wire:model.defer="group.members.{{ $index }}.gender"
                                            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                            <option value="">Select Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                    </div>

                                    <x-input label="Date of Birth" type="date"
                                        wire:model.defer="group.members.{{ $index }}.date_of_birth" />
                                    <x-input label="Nationality"
                                        wire:model.defer="group.members.{{ $index }}.nationality" />
                                    <x-input label="City"
                                        wire:model.defer="group.members.{{ $index }}.city" />
                                    <x-input label="Address"
                                        wire:model.defer="group.members.{{ $index }}.address" />
                                    <x-input label="Company"
                                        wire:model.defer="group.members.{{ $index }}.company" />

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ID Type</label>
                                        <select wire:model="group.members.{{ $index }}.id_type"
                                            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                            <option value="">Select ID Type</option>
                                            <option value="Ghana Card">Ghana Card</option>
                                            <option value="Passport">Passport</option>
                                            <option value="Voter ID">Voter ID</option>
                                            <option value="Student ID">Student ID</option>
                                            <option value="Work ID">Work ID</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    @if ($member['id_type'] === 'Other')
                                        <x-input label="Specify ID Type"
                                            wire:model.defer="groupMembersIdTypeOther.{{ $index }}" />
                                    @endif

                                    <x-input label="ID Number"
                                        wire:model.defer="group.members.{{ $index }}.id_number" />

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Visitor Type</label>
                                        <select wire:model.defer="group.members.{{ $index }}.visitor_type"
                                            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">
                                            <option value="">Select Visit Type</option>
                                            @foreach ($visitorType as $id => $label)
                                                <option value="{{ $id }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button wire:click="removeMember({{ $index }})"
                                        class="text-red-600 mt-6">Remove</button>
                                </div>
                            @endforeach

                            <button wire:click="addMember"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                + Add Member
                            </button>

                            <button wire:click="registerGroupVisitors"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                                Register Group
                            </button>
                        </div>
                    @endif
                </div>



        </main>
    </div>
</div>
<script>
    window.addEventListener('notify', event => {
        const { type, message } = event.detail;
        if (type === 'success') {
            toastr.success(message);
        } else if (type === 'error') {
            toastr.error(message);
        }
    });
</script>
