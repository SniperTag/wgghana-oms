@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Assign Permissions to Role: {{ $role->name }}</h2>

    <form action="{{ route('roles.assignPermissions.store', $role->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Assign Permissions</label><br>
            @foreach ($permissions as $permission)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input">
                    <label class="form-check-label">{{ $permission->name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Assign Permissions</button>
    </form>
</div>
@endsection
