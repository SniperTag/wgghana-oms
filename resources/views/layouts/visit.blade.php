<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.app')        {{-- Meta tags, title, base CSS --}}
    {{--  @include('layouts.visitCss')   Additional CSS for Visitor pages  --}}
    @livewireStyles                {{-- Livewire styles if needed --}}
</head>
<body>

 
 

        <div class="flex-grow-1 d-flex flex-column">
          

            {{-- Main content area --}}
            <main>
                {{ $slot }}
            </main>
        </div>

    @include('layouts.js')         
    {{--  @include('layouts.visitJs')      --}}
    @livewireScripts              
</body>
</html>
