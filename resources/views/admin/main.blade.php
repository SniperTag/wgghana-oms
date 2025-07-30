<!-- Main Container -->
<main id="main-container content-full">
    <!-- Page Content -->
    <div class="content mt-7">
        <div class="row">
            <!-- Row #1 -->
            <div class="col-6 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-bag fa-2x text-primary-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-primary">{{ $visitorCount }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Total visitors</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('attendance.index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-wallet fa-2x text-earth-light"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-earth">{{ $attendanceCount }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Attance Record</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Staff Currently On Leave -->
            <div class="col-6 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow"
                    href="{{ route('leaves.status', ['view' => 'current']) }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fas fa-user-clock fa-2x text-primary"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-primary">{{ $onLeaveCount }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">On Leave Now</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Staff Going On Leave -->
            <div class="col-6 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow"
                    href="{{ route('leaves.status', ['view' => 'upcoming']) }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="fas fa-calendar-alt fa-2x text-info"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-info">{{ $upcomingLeaveCount }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Upcoming Leaves</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('admin.users_index') }}">
                    <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                        <div class="d-none d-sm-block">
                            <i class="si si-users fa-2x text-pulse"></i>
                        </div>
                        <div class="text-end">
                            <div class="fs-3 fw-semibold text-pulse">{{ $userCount }}</div>
                            <div class="fs-sm fw-semibold text-uppercase text-muted">Users</div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <!-- Left: Step-Out Monitor (1/3 on large screens) -->
                <div class="col-span-1">
                    @role('admin|hr|supervisor')
                        <livewire:step-out-monitor />
                    @endrole
                </div>

                <!-- Right: Toggleable History/Report View (2/3 on large screens) -->
                <div class="col-span-1 lg:col-span-2" x-data="{ view: 'history' }">
                    @role('admin|hr|supervisor')
                        <!-- Toggle Button -->
                        <div class="flex justify-end mb-3">
                            <button @click="view = view === 'history' ? 'report' : 'history'"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                <span
                                    x-text="view === 'history' ? 'Switch to Report View' : 'Switch to History View'"></span>
                            </button>
                        </div>

                        <!-- Livewire Components Toggle -->
                        <div x-show="view === 'history'" x-cloak>
                            <livewire:step-out-history />
                        </div>

                        <div x-show="view === 'report'" x-cloak>
                            <livewire:step-out-report />
                        </div>
                    @endrole

                </div>

            </div>


            <!-- END Row #1 -->

            {{--  <div class="row">
                <!-- Row #2 -->
                <div class="col-md-6">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default border-bottom">
                            <h3 class="block-title">
                                Sales <small>This week</small>
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-toggle="block-option"
                                    data-action="state_toggle" data-action-mode="demo">
                                    <i class="si si-refresh"></i>
                                </button>
                                <button type="button" class="btn-block-option">
                                    <i class="si si-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="pull pt-5">
                                <!-- Lines Chart Container functionality is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _js/pages/be_pages_dashboard.js -->
                                <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                                <canvas id="js-chartjs-dashboard-lines" style="height: 290px"></canvas>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="row items-push text-center">
                                <div class="col-6 col-sm-4">
                                    <div class="fw-semibold text-success">
                                        <i class="fa fa-caret-up"></i> +16%
                                    </div>
                                    <div class="fs-4 fw-semibold">720</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">This Month</div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="fw-semibold text-danger">
                                        <i class="fa fa-caret-down"></i> -3%
                                    </div>
                                    <div class="fs-4 fw-semibold">160</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">This Week</div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="fw-semibold text-success">
                                        <i class="fa fa-caret-up"></i> +9%
                                    </div>
                                    <div class="fs-4 fw-semibold">24.3</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Average</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default border-bottom">
                            <h3 class="block-title">
                                Earnings <small>This week</small>
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-toggle="block-option"
                                    data-action="state_toggle" data-action-mode="demo">
                                    <i class="si si-refresh"></i>
                                </button>
                                <button type="button" class="btn-block-option">
                                    <i class="si si-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="pull pt-5">
                                <!-- Lines Chart Container functionality is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _js/pages/be_pages_dashboard.js -->
                                <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                                <canvas id="js-chartjs-dashboard-lines2" style="height: 290px"></canvas>
                            </div>
                        </div>
                        <div class="block-content bg-body-extra-light">
                            <div class="row items-push text-center">
                                <div class="col-6 col-sm-4">
                                    <div class="fw-semibold text-success">
                                        <i class="fa fa-caret-up"></i> +4%
                                    </div>
                                    <div class="fs-4 fw-semibold">$ 6,540</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">This Month</div>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="fw-semibold text-danger">
                                        <i class="fa fa-caret-down"></i> -7%
                                    </div>
                                    <div class="fs-4 fw-semibold">$ 1,525</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">This Week</div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="fw-semibold text-success">
                                        <i class="fa fa-caret-up"></i> +35%
                                    </div>
                                    <div class="fs-4 fw-semibold">$ 9,352</div>
                                    <div class="fs-sm fw-semibold text-uppercase text-muted">Balance</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Row #2 -->
            </div>  --}}
            <div class="row">
                <!-- Row #3 -->
                <div class="col-md-6">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default border-bottom">
                            <h2 class="block-title fs-4 font-serif font-extrabold">DAILY ATTENDANCE LIST</h2>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-toggle="block-option"
                                    data-action="state_toggle" data-action-mode="demo">
                                    <i class="si si-refresh"></i>
                                </button>
                                <button type="button" class="btn-block-option">
                                    <i class="si si-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">ID</th>
                                        <th>Status</th>
                                        <th class="d-none d-sm-table-cell">STAFFS</th>
                                        <th class="d-none d-sm-table-cell text-end">TIME IN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todayAttendance as $record)
                                        <tr>
                                            <td>
                                                {{ $record->id }}
                                            </td>
                                            <td>
                                                @if ($record->status == 'On Time')
                                                    <span
                                                        class="text-green-600 font-semibold">{{ $record->status }}</span>
                                                @elseif ($record->status == 'Late')
                                                    <span
                                                        class="text-yellow-600 font-semibold">{{ $record->status }}</span>
                                                @else
                                                    <span
                                                        class="text-red-600 font-semibold">{{ $record->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $record->user->name }}</td>

                                            <td class="d-none d-sm-table-cell text-end">
                                                {{ $record->check_in_time ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>


                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default border-bottom">
                            <h3 class="block-title block-title fs-4 font-serif font-extrabold">STAFFS ASSESMENT RATING
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-toggle="block-option"
                                    data-action="state_toggle" data-action-mode="demo">
                                    <i class="si si-refresh"></i>
                                </button>
                                <button type="button" class="btn-block-option">
                                    <i class="si si-wrench"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th class="d-none d-sm-table-cell" style="width: 100px;">ID</th>
                                        <th>Product</th>
                                        <th class="text-center">Orders</th>
                                        <th class="d-none d-sm-table-cell text-center">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="d-none d-sm-table-cell">
                                            <a class="fw-semibold" href="be_pages_ecom_product_edit.html">PID.258</a>
                                        </td>
                                        <td>
                                            <a href="be_pages_ecom_product_edit.html">Dark Souls III</a>
                                        </td>
                                        <td class="text-center">
                                            <a class="text-gray-dark" href="be_pages_ecom_orders.html">912</a>
                                        </td>
                                        <td class="d-none d-sm-table-cell text-center">
                                            <div class="text-warning">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END Row #3 -->
            </div>
        </div>
        <!-- END Page Content -->
</main>
