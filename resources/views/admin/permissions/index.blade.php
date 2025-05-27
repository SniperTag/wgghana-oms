<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('layouts.head')
</head>

<body>
    <!-- Page Container -->


    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">

        <!-- Sidebar -->

        {{-- Side bar dashboard start --}}

        @include('layouts.partials.sidebar')

        {{-- Side bar dashboard End --}}

        {{-- Side bar dashboard start --}}

        {{-- Side bar dashboard End --}}



        {{-- Header Section --}}
        @include('layouts.header')

        <!-- Main Container -->
        <main id="main-container content-full">
            <!-- Page Content -->
            <div class="content mt-7">
                <div class="row">


                    <div class="container-fluid">
<div class="container">
    <h4>Permissions</h4>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">Create New</a>

    <table class="table table-bordered">
        <thead>
            <tr><th>Name</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <!-- Trigger Edit Modal -->
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPermissionModal{{ $permission->id }}">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this permission?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>

                        <!-- Edit Permission Modal -->
                        <div class="modal fade" id="editPermissionModal{{ $permission->id }}" tabindex="-1" aria-labelledby="editPermissionLabel{{ $permission->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('permissions.update', $permission) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPermissionLabel{{ $permission->id }}">Edit Permission</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name{{ $permission->id }}" class="form-label">Permission Name</label>
                                                <input type="text" name="name" id="name{{ $permission->id }}" class="form-control" value="{{ $permission->name }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Modal -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
                    </div>

                </div>

            </div>
            <!-- END Page Content -->
        </main>
        {{-- Main section --}}

        <!-- END Main Container -->
        @include('layouts.footer')
    </div>
    <!-- END Page Container -->

    <script>
        $(document).ready(function() {
            $('#roles').select2({
                placeholder: "Select role(s)",
                width: '100%'
            });
        });

        $(document).ready(function() {
            $('#department').select2({
                placeholder: "Select department(s)",
                width: '100%'
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const triggerTabList = [].slice.call(document.querySelectorAll('#accessTabs a'))
            triggerTabList.forEach(function(triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })
        });
    </script>

    <!-- Select2 Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
