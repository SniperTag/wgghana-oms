<!-- Modernized Dashboard -->
<style>
    .dashboard-card {
        min-height: 120px;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
</style>

<main id="main-container" class="content-full">
    <div class="content mt-7">

        <!-- Top Statistics -->
        <div class="row g-3 mb-5 text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a class="dashboard-card d-block p-3 bg-white" href="{{ route('visitor.dashboard') }}">
                    <i class="si si-bag fa-2x text-primary mb-2"></i>
                    <h4 class="fw-bold">{{ $visitorCount }}</h4>
                    <small class="text-muted text-uppercase">Visitors</small>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a class="dashboard-card d-block p-3 bg-white" href="{{ route('attendance.index') }}">
                    <i class="si si-wallet fa-2x text-success mb-2"></i>
                    <h4 class="fw-bold">{{ $attendanceCount }}</h4>
                    <small class="text-muted text-uppercase">Attendance</small>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a class="dashboard-card d-block p-3 bg-white" href="{{ route('leaves.status', ['view' => 'current']) }}">
                    <i class="fas fa-user-clock fa-2x text-warning mb-2"></i>
                    <h4 class="fw-bold">{{ $onLeaveCount }}</h4>
                    <small class="text-muted text-uppercase">On Leave</small>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a class="dashboard-card d-block p-3 bg-white" href="{{ route('leaves.status', ['view' => 'upcoming']) }}">
                    <i class="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                    <h4 class="fw-bold">{{ $upcomingLeaveCount }}</h4>
                    <small class="text-muted text-uppercase">Upcoming Leaves</small>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a class="dashboard-card d-block p-3 bg-white" href="{{ route('all.staffs') }}">
                    <i class="si si-users fa-2x text-danger mb-2"></i>
                    <h4 class="fw-bold">{{ $userCount }}</h4>
                    <small class="text-muted text-uppercase">Users</small>
                </a>
            </div>
        </div>
        <!-- End Statistics -->

        <!-- Step-Out Monitor & Report Tabs -->
        @role('super_admin|admin|hr|supervisor')
        <div class="row mb-5">
            <div class="col-lg-4 mb-4">
                <livewire:step-out-monitor />
            </div>
            <div class="col-lg-8">
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#history">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#report">Report</a>
                    </li>
                </ul>
                <div class="tab-content bg-white p-3 rounded shadow-sm">
                    <div class="tab-pane fade show active" id="history">
                        <livewire:step-out-history />
                    </div>
                    <div class="tab-pane fade" id="report">
                        <livewire:step-out-report />
                    </div>
                </div>
            </div>
        </div>
        @endrole

        <!-- Attendance & Assessment -->
        <div class="row">
            <!-- Daily Attendance -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">Daily Attendance ({{ now()->format('d M, Y') }})</div>
                    <div class="card-body p-0">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Staff</th>
                                    <th class="text-end">Time In</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($todayAttendance as $record)
                                    <tr>
                                        <td>{{ $record->id }}</td>
                                        <td>
                                            @if ($record->status == 'On Time')
                                                <span class="badge bg-success">{{ $record->status }}</span>
                                            @elseif ($record->status == 'Late')
                                                <span class="badge bg-warning text-dark">{{ $record->status }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $record->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $record->user->name }}</td>
                                        <td class="text-end">{{ $record->check_in_time ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No records today</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Staff Assessment Rating -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">Staff Assessment Rating</div>
                    <div class="card-body p-0">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Staff</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Example row - replace with @forelse --}}
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td class="text-center">85</td>
                                    <td class="text-center">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-muted"></i>
                                        <i class="fa fa-star text-muted"></i>
                                    </td>
                                </tr>
                                {{-- @empty ... --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Trend Chart -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-bold">Weekly Attendance Trend</div>
            <div class="card-body">
                <div id="attendanceChart" style="height: 250px;"></div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js Example -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var options = {
        chart: { type: 'line', height: 250 },
        series: [{
            name: 'Attendance',
            data: [5, 8, 6, 9, 7, 10, 12] // replace with dynamic data
        }],
        xaxis: { categories: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] }
    };
    new ApexCharts(document.querySelector("#attendanceChart"), options).render();
});
</script>
