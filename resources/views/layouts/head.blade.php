<!-- Meta Configuration -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Page Title -->
<title>{{ $title ?? 'WGGHANA OFFICE MANAGEMENT SYSTEM' }}</title>
<!-- Page-Specific Styles -->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net" />
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Favicon Icons -->
<link rel="shortcut icon" href="{{ asset('build/assets/media/favicons/022.png') }}" />
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('build/assets/media/favicons/022.png') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('build/assets/media/favicons/022.png') }}" />

<!-- Core CSS -->
<link rel="stylesheet" id="css-main" href="{{ asset('build/assets/css/codebase.min.css') }}" />


<!-- Laravel Vite Assets -->
@vite(['resources/css/app.css'])



<!-- Livewire Styles -->


@livewireStyles
@stack('styles')
