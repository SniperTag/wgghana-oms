
    @php
        $user = Auth::user();
        $notifications = $user?->unreadNotifications;
        if ($notifications && $notifications->count()) {
            $user->unreadNotifications->markAsRead();
        }
    @endphp

    <div id="page-container"
         class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Header --}}
        @include('layouts.header')

        {{-- Main Content injected by Livewire --}}
        <main class="main-content">
           @include('admin.main')
        </main>

       
    </div>
