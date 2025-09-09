<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app')
    <title>Project & Task Dashboard</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>


    <style>
        .small-chart {
            max-width: 450px;
            /* or any desired width */
            max-height: 700px;
            /* maintain aspect ratio */
            margin: auto;
        }

        .dashboard-padding {
            padding-right: 6rem;
            padding-left:6rem;
            /* Equivalent to py-8 */
            padding-bottom: 6rem;
        }

        .small-chart {
            max-width: 250px;
            max-height: 250px;
            margin: auto;
            display: block;

            
        }
        /* Make modal backdrop transparent */
.modal-backdrop.show {
    background-color: transparent;
}

/* Optional: Remove modal dialog background */
.modal-content {
    background-color: rgba(255, 255, 255, 0.9); /* slightly transparent white */
    border: none; /* remove border if needed */
}
    </style>

</head>

<body>

    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay
        side-scroll page-header-fixed
        page-header-modern main-content-boxed">

        @include('layouts.header')

        @include('layouts.partials.sidebar')

        <div class="container-fluid dashboard-padding">
            <!-- KPI Cards -->
            <div class="row g-3 mb-4 mt-7">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">📂 Project & Task Dashboard</h2>
                   <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                + New Project
            </button>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Total Projects</div>
                        <div class="fs-4 fw-bold">120</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Active Tasks</div>
                        <div class="fs-4 fw-bold text-primary">87</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Overdue Tasks</div>
                        <div class="fs-4 fw-bold text-danger">14</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Completed</div>
                        <div class="fs-4 fw-bold text-success">62</div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-1 mb-4">
                <div class="col-lg-6">
                    <div class="card p-3 shadow-sm">
                        <h6 class="fw-bold mb-2">Projects by Status</h6>
                        <canvas id="projectsChart" class="small-chart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-3 shadow-sm">
                        <h6 class="fw-bold mb-2">Tasks Progress Trend</h6>
                        <canvas id="tasksChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="card p-3 shadow-sm">
                <h6 class="fw-bold mb-2">Active Projects</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Project</th>
                                <th>Manager</th>
                                <th>Progress</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ERP Upgrade</td>
                                <td>John Doe</td>
                                <td>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-primary" style="width:65%"></div>
                                    </div>
                                </td>
                                <td>15 Sep 2025</td>
                                <td><span class="badge bg-success">On Track</span></td>
                            </tr>
                            <tr>
                                <td>Payroll System</td>
                                <td>Sarah Lee</td>
                                <td>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-warning" style="width:40%"></div>
                                    </div>
                                </td>
                                <td>30 Sep 2025</td>
                                <td><span class="badge bg-warning text-dark">At Risk</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal: Create New Project -->
<div class="modal fade mt-5" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel">+ New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Project Manager</label>
                        <select name="manager_id" class="form-select" required>
                            <option value="">Select Manager</option>
                            <option value="1">John Doe</option>
                            <option value="2">Sarah Lee</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="date" name="deadline" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="on_track">On Track</option>
                            <option value="at_risk">At Risk</option>
                            <option value="delayed">Delayed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Create Project</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
            @include('layouts.js')
        </div>
    </div>

    <script>
        new Chart(document.getElementById("projectsChart"), {
            type: 'doughnut',
            data: {
                labels: ["On Track", "At Risk", "Overdue", "Completed"],
                datasets: [{
                    data: [45, 20, 12, 63],
                    backgroundColor: ["#198754", "#ffc107", "#dc3545", "#6c757d"]
                }]
            }
        });

        new Chart(document.getElementById("tasksChart"), {
            type: 'line',
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                datasets: [{
                    label: "Tasks Completed",
                    data: [20, 25, 22, 30, 28, 35],
                    borderColor: "#0d6efd",
                    fill: false
                }]
            }
        });
    </script>
</body>

</html>
