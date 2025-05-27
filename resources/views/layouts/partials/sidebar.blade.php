<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Side Header -->
        <div class="content-header justify-content-lg-center">
            <!-- Logo -->
            <div>
                <span class="smini-visible fw-bold tracking-wide fs-lg">
                    c<span class="text-primary">b</span>
                </span>
                <a class="link-fx fw-bold tracking-wide mx-auto" href="{{ route('dashboard') }}">
                    <span class="smini-hidden">
                        <i class="fas fa-envelope text-primary"></i>
                        <span class="fs-4 text-dual">WG</span><span class="fs-4 text-primary">GHANA</span>
                    </span>
                </a>
            </div>
            <!-- END Logo -->

            <!-- Options -->
            <div>
                <!-- Close Sidebar (for mobile) -->
                <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout"
                    data-action="sidebar_close">
                    <i class="fa fa-fw fa-times"></i>
                </button>
            </div>
            <!-- END Options -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
            <!-- Side User -->
            <div class="content-side content-side-user px-0 py-0">
                <div class="smini-visible-block animated fadeIn px-3">
                    <img class="img-avatar img-avatar32" src="{{ asset('build/assets/media/avatars/avatar15.jpg') }}"
                        alt="">
                </div>
                <div class="smini-hidden text-center mx-auto">
                    <a class="img-link" href="{{ route('profile.update') }}">
                        <img class="img-avatar" src="{{ asset('build/assets/media/avatars/avatar15.jpg') }}"
                            alt="">
                    </a>
                    <ul class="list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a class="link-fx text-dual fs-sm fw-semibold text-uppercase"
                                href="{{ route('profile.update') }}">
                                {{ auth()->user()->name }}
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-fx text-dual" data-toggle="layout" data-action="dark_mode_toggle"
                                href="javascript:void(0)">
                                <i class="fa fa-moon"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-fx text-dual" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END Side User -->

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">

                    <!--####################################################################Managers Start Section####################################################################################-->

                    <!-- Dashboard -->
                    @role('admin')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="nav-main-link-icon fa fa-house-user"></i>
                                <span class="nav-main-link-name">Dashboard</span>
                            </a>
                        </li>
                    @endrole
                    <!-- User Management -->
                    @hasanyrole('admin|hr|manager')
                        <li class="nav-main-item {{ request()->routeIs('roles.*') ? 'open' : '' }}">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-award"></i>
                                <span class="nav-main-link-name">User Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{ request()->routeIs('admin.create') ? 'active' : '' }}"
                                        href="{{ route('admin.users.create') }}">
                                        <span class="nav-main-link-name">Create Staff</span>
                                    </a>
                                </li>


                                <li class="nav-main-item">
                                    <a class="nav-main-link {{ request()->routeIs('invite.user') ? 'active' : '' }}"
                                        href="{{ route('admin.invite.user') }}">
                                        <span class="nav-main-link-name">Invite Staff</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                                        href="{{ route('admin.users.index') }}">
                                        <span class="nav-main-link-name">View All Staffs</span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <!-- Access Controller -->
                        <li class="nav-main-item">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-eye"></i>
                                <span class="nav-main-link-name">Access Controller</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('roles.create') }}">
                                        <span class="nav-main-link-name">Create Roles</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('access.management') }}">
                                        <span class="nav-main-link-name">Access Management</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('permissions.create') }}">
                                        <span class="nav-main-link-name">Create Permissions</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Attendance Section-->
                        <li class="nav-main-heading">Attendance & Time</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-grip-vertical"></i>
                                <span class="nav-main-link-name">Attendance Records</span>
                            </a>
                            <ul class="nav-main-submenu">

                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('attendance.index') }}">
                                        <span class="nav-main-link-name">View All Attendance</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('admin.attendance') }}">
                                        <span class="nav-main-link-name">My Attendance</span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <!-- Leave Management -->
                        <li class="nav-main-heading">Leave Management</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-calendar"></i>
                                <span class="nav-main-link-name">Leave Records</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('leaves.index') }}">
                                        <span class="nav-main-link-name">View All Leave</span>
                                    </a>
                                </li>

                                 <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('leaves.index') }}">
                                        <span class="nav-main-link-name">View Pending Leaves</span>
                                    </a>
                                </li>

                                {{--  <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('leaves.create') }}">
                                        <span class="nav-main-link-name">Create Leave</span>
                                    </a>
                                </li>  --}}
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('leave_balances.create', $user->id) }}">
                                        <span class="nav-main-link-name">Create Leave Balance</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Staff Management -->
                        <li class="nav-main-heading">Staff Management</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-vector-square"></i>
                                <span class="nav-main-link-name">Assessment</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                        <span class="nav-main-link-name">Projects</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Create Project</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Assign Staffs</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">View Projects</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                        <span class="nav-main-link-name">Tasks Section</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Create Task</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Assign Task</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">View Tasks</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endhasanyrole

                    <!--####################################################################Staff Start Section####################################################################################-->


                    <!-- staff Access and RolesPermissions -->
                    @role('staff')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('staff/dashboard') ? 'active' : '' }}"
                                href="{{ route('staff.dashboard') }}">
                                <i class="nav-main-link-icon fa fa-house-user"></i>
                                <span class="nav-main-link-name">Dashboard</span>
                            </a>
                        </li>

                        <!-- Attendance Section-->
                        <li class="nav-main-heading">Attendance & Time</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('self.attendance') }}">
                                <i class="nav-main-link-icon fa fa-book"></i>
                                <span class="nav-main-link-name">My Attendance</span>
                            </a>
                        </li>

                        <!-- Leave Management -->
                        <li class="nav-main-heading">Leave Management</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('staff.leave.apply') }}">
                                <i class="nav-main-link-icon fa fa-pencil"></i>
                                <span class="nav-main-link-name">Request Leave</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('staff.leaves.index') }}">
                                <i class="nav-main-link-icon fa fa-address-book-o"></i>
                                <span class="nav-main-link-name">My Leaves</span>
                            </a>
                        </li>
                    @endrole


                    <!--####################################################################Supervisor Start Section####################################################################################-->
                    <!-- Supervisor Home -->
                    @role('supervisor')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('supervisor/dashboard') ? 'active' : '' }}"
                                href="{{ route('supervisor.dashboard') }}">
                                <i class="nav-main-link-icon fa fa-house-user"></i>
                                <span class="nav-main-link-name">Dashboard</span>
                            </a>
                        </li>

                        <!-- Attendance Section-->
                        <li class="nav-main-heading">Attendance & Time</li>
                        <li class="nav-main-item">

                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('supervisor.self.attendance') }}">
                                <i class="nav-main-link-icon fa fa-book"></i>
                                <span class="nav-main-link-name">My Attendance</span>
                            </a>
                        </li>


                        </li>

                        <li class="nav-main-heading">Leave Management</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-calendar"></i>
                                <span class="nav-main-link-name">Leave Records</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('supervisor.leaves.index') }}">
                                        <span class="nav-main-link-name">My Leaves History</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('supervisor.subordinates.index') }}">
                                        Pending Leaves
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ route('supervisor.leaves.create') }}">
                                        <span class="nav-main-link-name">Request Leave</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Staff Management -->
                        <li class="nav-main-heading">Staff Management</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                <i class="nav-main-link-icon fa fa-vector-square"></i>
                                <span class="nav-main-link-name">Assessment</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                        <span class="nav-main-link-name">Projects</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Create Project</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Assign Staffs</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">View Projects</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                                        <span class="nav-main-link-name">Tasks Section</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Create Task</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">Assign Task</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="#">
                                                <span class="nav-main-link-name">View Tasks</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endrole


                    <!--####################################################################Finance Start Section####################################################################################-->

                    <!-- finance Home -->
                    @role('finance')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('finance/dashboard') ? 'active' : '' }}"
                                href="{{ route('finance.dashboard') }}">
                                <i class="nav-main-link-icon fa fa-house-user"></i>
                                <span class="nav-main-link-name">Dashboard</span>
                            </a>
                        </li>
                    @endrole





                    @hasanyrole('admin|hr|manager|supervisor')
                    @endhasanyrole
                    <!-- Leave Management -->
                    @hasanyrole('admin|hr|manager')
                    @endhasanyrole


                    <!-- END Leave Management -->
                </ul>

            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </div>
    <!-- END Sidebar Content -->
</nav>
