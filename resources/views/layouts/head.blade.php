<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'WGGHANA OFFICE MANAGEMENT SYSTEM' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- External Css --}}
{{-- Icons --}}
<link rel="shortcut icon" href="{{ asset('build/assets/media/favicons/favicon.png') }}">
<link rel="icon" type="image/png" sizes="192x192"
    href="{{ asset('build/assets/media/favicons/favicon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="180x180"
    href="{{ asset('build/assets/media/favicons/apple-touch-icon-180x180.png') }}">
<!-- END Icons -->
{{--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">  --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<!-- Stylesheets -->
<!-- Codebase framework -->
<link rel="stylesheet" id="css-main" href="{{ asset('build/assets/css/codebase.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Custom CSS -->
{{-- Data Table Css --}}
{{--  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />  --}}


@vite(['resources/css/app.css', 'resources/js/app.js'])


