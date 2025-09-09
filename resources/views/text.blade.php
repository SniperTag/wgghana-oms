<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @php
    use App\Models\Department;
    $departments = Department::orderBy('name')->get();
@endphp

<div class="col-md-3">
    <label class="form-label">Department</label>
    <select
        name="department_id"
        id="department"
        class="form-select"
        required
        {{-- store old values as data attributes for JS pre-population --}}
        data-old-dept="{{ old('department_id') }}"
        data-old-subrole="{{ old('sub_role_id') }}"
    >
        <option value="">--Select Department--</option>
        @foreach ($departments as $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
    </select>
    @error('department_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-3">
    <label class="form-label">Sub Role</label>
    <select name="sub_role_id" id="sub_role" class="form-select" required disabled>
        <option value="">--Select Sub Role--</option>
    </select>
    @error('sub_role_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

{{-- Place this script AFTER @include('layouts.js') so jQuery exists. If not using jQuery, the fetch fallback below will still work. --}}


</body>
</html>
