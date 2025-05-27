{{--  <x-layouts.app :title="__('Dashboard')">


</x-layouts.app>  --}}




<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <!-- Page Container -->
  
    
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
