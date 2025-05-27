
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
                        <div class="row justify-content-center">
                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-header"></div>

                                    <div class="container">
    <h2 class="mb-4">Access Management</h2>

    <ul class="nav nav-tabs" id="accessTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Roles</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="role-permissions-tab" data-bs-toggle="tab" data-bs-target="#role-permissions" type="button" role="tab">Role Permissions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="user-access-tab" data-bs-toggle="tab" data-bs-target="#user-access" type="button" role="tab">User Access</button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="accessTabsContent">
        {{-- Roles Tab --}}
        <div class="tab-pane fade show active" id="roles" role="tabpanel">
            @include('admin.access.partials.roles')
        </div>

        {{-- Role Permissions Tab --}}
        <div class="tab-pane fade" id="role-permissions" role="tabpanel">
            @include('admin.access.partials.role-permissions')
        </div>

        {{-- User Access Tab --}}
        <div class="tab-pane fade" id="user-access" role="tabpanel">
            @include('admin.access.partials.user-access')
        </div>
    </div>
</div>
                                </div>

                            </div>
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

         document.addEventListener("DOMContentLoaded", function () {
        const triggerTabList = [].slice.call(document.querySelectorAll('#accessTabs a'))
        triggerTabList.forEach(function (triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    });
    </script>

    <!-- Select2 Plugin -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  --}}

    <!-- Bootstrap Bundle (Popper.js included) -->
    {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  --}}

</body>

</html>
