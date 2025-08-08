<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.app')
    @livewireStyles
</head>
<body>
    <!-- You can add header, sidebar here if needed -->
    {{ $slot }}

    @include('layouts.js')
    <!-- Add your JS files here -->
    @livewireScripts
</body>
</html>
