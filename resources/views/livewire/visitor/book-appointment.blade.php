<div id="page-container"
    class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed"
    style="background-image: url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;">

    @if (Auth::check())
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Header -->
        @include('layouts.header')
    @endif

    <!-- Main Container -->

    <main id="main-container" class="content-full">
        <div class="page-container d-flex flex-column min-vh-100">

            <div class="relative min-h-screen p-6"
                >


                {{-- Dark overlay to improve readability --}}
                <div class="absolute inset-0 bg-gray-600 opacity-50 pointer-events-none"></div>


                {{-- Content wrapper above overlay --}}
                <div class="relative z-10 max-w-4xl mx-auto">
                    {{-- Back Button --}}
                    <div class="mb-6">
                        @auth
                            <a href="{{ route('visitor.dashboard') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow inline-flex items-center">
                                <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back to Home Page
                            </a>
                        @else
                            <button type="button" onclick="unauthorizedRedirect()"
                                class="bg-gray-400 cursor-not-allowed text-white font-semibold py-2 px-4 rounded-md shadow inline-flex items-center">
                                <i class="fa fa-lock mr-2" aria-hidden="true"></i> Back to Home Page
                            </button>
                        @endauth
                    </div>


                    {{-- Step 2: Appointment Booking Form (background) --}}
                    @if ($step === 2)
                        <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Book Appointment</h3>

                            <form wire:submit.prevent="submit" class="space-y-4">
                                <input wire:model="visitor_name" type="text" class="form-control"
                                    placeholder="Visitor Name" readonly>
                                <input wire:model="visitor_email" type="email" class="form-control"
                                    placeholder="Visitor Email" readonly>
                                <input wire:model="visitor_phone" type="text" class="form-control"
                                    placeholder="Visitor Phone" readonly>

                                <div class="mb-3">
                                    <label for="department_id" class="font-semibold">Select Department</label>
                                    <select wire:model="department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="host_id" class="font-semibold">Select Host</label>
                                    <select id="host-select" wire:model="host_id" class="form-control">
                                        <option value="">Select Host</option>
                                        @foreach ($hosts as $host)
                                            <option value="{{ $host->id }}">{{ $host->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('host_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="font-semibold">Visitor Type (Title)</label>
                                    <select id="visitor-type-select" wire:model="title" class="form-control">
                                        <option value="">Select Visitor Type</option>
                                        @foreach ($visitorType as $type)
                                            <option value="{{ $type->name }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label for="description" class="font-semibold">Meeting Description</label>
                                    <textarea wire:model="description" class="form-control" placeholder="Meeting Description"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="date" class="font-semibold">Date</label>
                                    <input wire:model="date" type="date" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="time" class="font-semibold">Time</label>
                                    <input wire:model="time" type="time" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="meeting_type" class="font-semibold">Meeting Type</label>
                                    <select wire:model="meeting_type" class="form-control">
                                        <option value="Physical">Physical</option>
                                        <option value="Virtual">Virtual</option>
                                    </select>
                                </div>

                                <input wire:model="location" type="text" class="form-control" placeholder="Location">

                                <div wire:loading class="text-sm text-muted mt-2">Booking appointment, please wait...
                                </div>

                                <button type="submit" class="btn btn-success w-full" wire:loading.attr="disabled"
                                    wire:target="submit">
                                    <span wire:loading.remove wire:target="submit">Book Appointment</span>
                                    <span wire:loading wire:target="submit">
                                        <i class="fa fa-spinner fa-spin"></i> Booking...
                                    </span>
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Step 1: Visitor Identity Verification Modal --}}
                    @if ($step === 1)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-transparent bg-opacity-80 backdrop-blur-sm"
                            style="backdrop-filter: blur(4px);">

                            <div
                                class="bg-gray-700 bg-opacity-95 px-6 py-4 rounded-lg shadow-xl w-full max-w-xl mx-4 text-white">
                                <h3 class="text-2xl font-semibold mb-4 text-center">Visitor Identity Verification</h3>

                                <form wire:submit.prevent="searchVisitor" class="space-y-5 w-full">
                                    <input wire:model="search_input" type="text"
                                        placeholder="Enter Phone, ID or Visitor UID"
                                        class="w-full text-base p-3 rounded-md border border-gray-500 bg-transparent text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                    @error('search_input')
                                        <span class="text-red-400 text-sm">{{ $message }}</span>
                                    @enderror

                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-md py-2 text-base font-semibold"
                                        wire:loading.attr="disabled" wire:target="searchVisitor">
                                        <span wire:loading.remove wire:target="searchVisitor">Search Visitor</span>
                                        <span wire:loading wire:target="searchVisitor">
                                            <i class="fa fa-spinner fa-spin mr-2"></i> Searching...
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Success & Error messages --}}
                    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-60 w-full max-w-lg px-4">
                        @if (session()->has('message'))
                            <div class="bg-green-600 text-white p-3 rounded shadow mb-2 text-center">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="bg-red-600 text-white p-3 rounded shadow mb-2 text-center">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            new TomSelect('#host-select', {
                maxItems: 1,
                onChange: function(value) {
                    @this.set('host_id', value);
                }
            });

            new TomSelect('#visitor-type-select', {
                maxItems: 1,
                onChange: function(value, option) {
                    @this.set('title', option.textContent);
                }
            });
        });
    </script>
@endpush
<script>
    function unauthorizedRedirect() {
        alert('You are not permmited to access this, Kindly contact the office, Thank you');
        window.location.href = "https://wgghana.com/index.html";
    }
</script>
