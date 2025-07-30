{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.app')
</head>

<body>
    <!-- Page Container -->
  {{--  <script>
    window.Echo.channel('staff-stepping')
        .listen('.staff.step.update', (e) => {
            const message = `${e.data.name} has ${e.data.type === 'step_out' ? 'stepped out' : 'returned'} at ${e.data.time}`;
            toastr.info(message, 'Staff Alert');
        });
</script>  --}}

@php
    $user = Auth::user();
    $notifications = $user?->unreadNotifications;

    if ($notifications && $notifications->count()) {
        $user->unreadNotifications->markAsRead();
    }
@endphp



    <div id="page-container"
        class="sidebar-o sidebar-light enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">



        {{-- Side bar dashboard start --}}

        @include('layouts.partials.sidebar')








        {{-- Header Section --}}
        @include('layouts.header')


        {{--Main section--}}
        @include('admin.main')
        <!-- END Main Container -->

        @include('layouts.js')
    </div>
    <!-- END Page Container -->



</body>

</html>
