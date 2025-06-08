{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <!-- Page Container -->
  <script>
    window.Echo.channel('staff-stepping')
        .listen('.staff.step.update', (e) => {
            const message = `${e.data.name} has ${e.data.type === 'step_out' ? 'stepped out' : 'returned'} at ${e.data.time}`;
            toastr.info(message, 'Staff Alert');
        });
</script>
    
@if(Auth::user()?->unreadNotifications?->count())
    @foreach (Auth::user()->unreadNotifications as $notification)
        <div class="alert alert-info mb-2">
            {{ $notification->data['message'] }} 
            <span class="text-muted small">{{ \Carbon\Carbon::parse($notification->data['time'])->diffForHumans() }}</span>
        </div>
    @endforeach
@endif

    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">
      
        
  
        {{-- Side bar dashboard start --}}

        @include('layouts.partials.sidebar')

        



        {{-- Header Section --}}
        @include('layouts.header')


        {{--Main section--}}
        @include('admin.main')
        <!-- END Main Container -->

        @include('layouts.footer')
    </div>
    <!-- END Page Container -->



</body>

</html>
