<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    @include('layouts.header')

    <!-- Main Container -->
    <main id="main-container" class="content-max-w-full">
        <div class="content py-4 px-4 sm:px-6 md:px-4 bg-gray-100 min-h-screen">
            <!-- Full Width -->
            <div class="w-full bg-white p-4 sm:p-4 rounded-2xl shadow-lg border border-gray-100">

                <!-- Header -->
                <h2 class="text-3xl font-bold text-gray-800 mb-2 text-center">Visitor Registration</h2>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Please fill in visitor details accurately. Fields marked with * are required.
                </p>

                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Toggle Buttons -->
                <div class="flex gap-4 mb-8 justify-start">
                    <button type="button" wire:click="switchToSingle"
                        class="{{ !$isTeam ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800' }} px-5 py-2 rounded-md shadow text-sm font-semibold transition">
                        Single
                    </button>
                    <button type="button" wire:click="switchToGroup"
                        class="{{ $isTeam ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800' }} px-5 py-2 rounded-md shadow text-sm font-semibold transition">
                        Group
                    </button>
                </div>
                <!-- Form -->
                <form wire:submit.prevent="registerVisitor" class="space-y-10">
                    @if (!$isTeam)
                        <!-- ✅ SINGLE VISITOR FORM -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-gray-700 font-medium mb-1">Full Name*</label>
                                <input type="text" wire:model.defer="full_name" id="full_name" required
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-gray-700 font-medium mb-1">Gender</label>
                                <select wire:model.defer="gender" id="gender"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-gray-700 font-medium mb-1">Date of
                                    Birth</label>
                                <input type="date" wire:model.defer="date_of_birth" id="date_of_birth"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Nationality -->
                            <div>
                                <label for="nationality"
                                    class="block text-gray-700 font-medium mb-1">Nationality</label>
                                <input type="text" wire:model.defer="nationality" id="nationality"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-gray-700 font-medium mb-1">Address</label>
                                <input type="text" wire:model.defer="address" id="address"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-gray-700 font-medium mb-1">City</label>
                                <input type="text" wire:model.defer="city" id="city"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-gray-700 font-medium mb-1">Email*</label>
                                <input type="email" wire:model.defer="email" id="email" required
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-gray-700 font-medium mb-1">Phone*</label>
                                <input type="text" wire:model.defer="phone" id="phone" required
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company" class="block text-gray-700 font-medium mb-1">Company</label>
                                <input type="text" wire:model.defer="company" id="company"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- ID Type -->
                            <div>
                                <label for="id_type" class="block text-gray-700 font-medium mb-1">ID Type*</label>
                                <select wire:model.defer="id_type" id="id_type" required
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select ID Type</option>
                                    @foreach (['Ghana Card', 'Passport', 'Student ID', 'Work ID', 'Driver License'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- ID Number -->
                            <div>
                                <label for="id_number" class="block text-gray-700 font-medium mb-1">ID Number*</label>
                                <input type="text" wire:model.defer="id_number" id="id_number" required
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Visitor Type -->
                            <div>
                                <label for="visitor_type_id" class="block text-gray-700 font-medium mb-1">Visitor
                                    Type*</label>
                                <select wire:model.defer="visitor_type_id" id="visitor_type_id" required
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select Visitor Type</option>
                                    @foreach (\App\Models\VisitorType::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Photo -->
                            <div>
                                <label for="photo" class="block text-gray-700 font-medium mb-1">Photo</label>
                                <input type="file" wire:model="photo" id="photo" accept="image/*"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>

                            <!-- Signature -->
                            <div>
                                <label for="signature" class="block text-gray-700 font-medium mb-1">Signature</label>
                                <input type="file" wire:model="signature" id="signature" accept="image/*"
                                    class="w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm" />
                            </div>
                        </div>
                    @else
                        <!-- ✅ GROUP VISITORS FORM -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Select Group Leader</label>
                            <select wire:model="leaderIndex"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Select Leader --</option>
                                @foreach ($teamVisitors as $index => $visitor)
                                    <option value="{{ $index }}">
                                        {{ $visitor['full_name'] ?: 'Visitor ' . ($index + 1) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-10">
                            @foreach ($teamVisitors as $index => $visitor)
                                <div class="border-b border-gray-200 pb-8">
                                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                                        Visitor {{ $index + 1 }}
                                        @if ($leaderIndex == $index)
                                            <span class="text-indigo-600 ml-2">(Leader)</span>
                                        @endif
                                    </h3>

                                    <!-- Visitor Fields -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-gray-700 mb-1">Full Name*</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.full_name"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Gender -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Gender</label>
                                            <select wire:model.defer="teamVisitors.{{ $index }}.gender"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <!-- Date of Birth -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Date of Birth</label>
                                            <input type="date"
                                                wire:model.defer="teamVisitors.{{ $index }}.date_of_birth"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Nationality -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Nationality</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.nationality"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Address -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Address</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.address"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- City -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">City</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.city"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Email*</label>
                                            <input type="email"
                                                wire:model.defer="teamVisitors.{{ $index }}.email"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Phone -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Phone</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.phone"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Company -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Company</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.company"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- ID Type -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">ID Type*</label>
                                            <select wire:model.defer="teamVisitors.{{ $index }}.id_type"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Select ID Type</option>
                                                @foreach (['Ghana Card', 'Passport', 'Student ID', 'Work ID', 'Driver License'] as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- ID Number -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">ID Number*</label>
                                            <input type="text"
                                                wire:model.defer="teamVisitors.{{ $index }}.id_number"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>

                                        <!-- Visitor Type -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Visitor Type*</label>
                                            <select
                                                wire:model.defer="teamVisitors.{{ $index }}.visitor_type_id"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Select Visitor Type</option>
                                                @foreach (\App\Models\VisitorType::all() as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Photo -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Photo</label>
                                            <input type="file" wire:model="teamVisitors.{{ $index }}.photo"
                                                accept="image/*"
                                                class="w-full border-gray-300 rounded-md shadow-sm" />
                                            @if (isset($teamVisitors[$index]['photo']) && $teamVisitors[$index]['photo'] instanceof \Livewire\TemporaryUploadedFile)
                                                <img src="{{ $teamVisitors[$index]['photo']->temporaryUrl() }}"
                                                    class="mt-3 w-24 h-24 rounded-lg border object-cover"
                                                    alt="Photo preview">
                                            @endif
                                        </div>

                                        <!-- Signature -->
                                        <div>
                                            <label class="block text-gray-700 mb-1">Signature</label>
                                            <input type="file"
                                                wire:model="teamVisitors.{{ $index }}.signature"
                                                accept="image/*"
                                                class="w-full border-gray-300 rounded-md shadow-sm" />
                                            @if (isset($teamVisitors[$index]['signature']) &&
                                                    $teamVisitors[$index]['signature'] instanceof \Livewire\TemporaryUploadedFile)
                                                <img src="{{ $teamVisitors[$index]['signature']->temporaryUrl() }}"
                                                    class="mt-3 w-24 h-24 rounded-lg border object-cover"
                                                    alt="Signature preview">
                                            @endif
                                        </div>
                                    </div>


                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 text-right">
                            <button type="button" wire:click="addTeamVisitor"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-md shadow">
                                + Add Another Visitor
                            </button>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="text-right pt-6 relative">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition disabled:opacity-70 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled" wire:target="registerVisitor">
                            Register Visitor
                        </button>

                        <!-- Loader Overlay -->
                        <div wire:loading wire:target="registerVisitor"
                            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 rounded-lg">
                            <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>


    <!-- END Main Container -->
</div>
