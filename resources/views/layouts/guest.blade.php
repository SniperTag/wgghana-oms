<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'WGGHANA OFFICE MANAGEMENT SYSTEM' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

 {{-- External Css --}}
{{-- Icons --}}
<link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('media/favicons/favicon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">
<!-- END Icons -->

<!-- Stylesheets -->
<!-- Codebase framework -->
<link rel="stylesheet" id="css-main" href="{{ asset('css/codebase.min.css') }}">



@vite(['resources/css/app.css', 'resources/js/app.js'])


