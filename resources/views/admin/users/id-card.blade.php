<!DOCTYPE html>
<html>
<head>
    <title>Staff ID Card</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .id-card {
            width: 350px;
            height: 220px;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        .photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .barcode {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <h3>{{ config('app.name') }}</h3>
        <img class="photo" src="{{ $staff->profile_photo_url ?? asset('images/default.png') }}" alt="Photo">
        <h4>{{ $staff->name }}</h4>
        <p><strong>Staff ID:</strong> {{ $staff->staff_id }}</p>
        <p><strong>Department:</strong> {{ $staff->department->name ?? 'N/A' }}</p>

        <div class="barcode">
            {!! DNS1D::getBarcodeHTML($staff->staff_id, 'C128') !!}
        </div>
    </div>
</body>
</html>
