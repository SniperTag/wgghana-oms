<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.app')
</head>
<body class="bg-gray-100 text-gray-900">
    {{ $slot }}
@include('layouts.js')
</body>
</html>
