<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app')
    <style>
        /* Make modal backdrop transparent */
        #taskModal~.modal-backdrop {
            background-color: transparent;
        }

        /* Optional: Slightly transparent modal content */
        #taskModal .modal-content {
            background-color: rgba(255, 255, 255, 0.95);
            /* adjust opacity as needed */
            border: none;
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

        <!-- Main Content -->
        <div class="container-fluid dashboard-padding">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">📂 Project Details: ERP Upgrade</h2>
                <a href="{{ route('create.project') }}" class="btn btn-secondary btn-sm">← Back to Projects</a>
            </div>

            <!-- Project Info Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <div class="text-muted">Project Manager</div>
                        <div class="fs-5 fw-bold">John Doe</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <div class="text-muted">Start Date</div>
                        <div class="fs-5 fw-bold">01 Aug 2025</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm">
                        <div class="text-muted">Deadline</div>
                        <div class="fs-5 fw-bold">15 Sep 2025</div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="card p-3 shadow-sm">
                        <h6 class="fw-bold mb-2">Tasks Status</h6>
                        <canvas id="tasksStatusChart" class="small-chart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-3 shadow-sm">
                        <h6 class="fw-bold mb-2">Progress Trend</h6>
                        <canvas id="progressTrendChart" class="small-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tasks Table with Add/Edit Buttons -->
            <div class="card p-3 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">Project Tasks</h6>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#taskModal">
                        + Add Task
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Assignee</th>
                                <th>Start Date</th>
                                <th>Deadline</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Database Upgrade</td>
                                <td>Jane Smith</td>
                                <td>01 Aug 2025</td>
                                <td>05 Aug 2025</td>
                                <td>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-success" style="width:100%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#taskModal">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Module Testing</td>
                                <td>Michael Brown</td>
                                <td>06 Aug 2025</td>
                                <td>12 Aug 2025</td>
                                <td>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-warning" style="width:70%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning text-dark">In Progress</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#taskModal">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            <!-- More tasks dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Team Members -->
           <div class="card p-3 shadow-sm mb-4">
    <h6 class="fw-bold mb-3">Team Members</h6>

    <!-- Search and Status Filter -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <input type="text" id="teamSearch" class="form-control" placeholder="Search team members..." style="max-width: 250px;">
        <select id="statusFilter" class="form-select" style="max-width: 200px;">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="on leave">On Leave</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="teamTableBody">
                <tr>
                    <td><img src="https://via.placeholder.com/50" class="rounded-circle" alt="John Doe"></td>
                    <td>John Doe</td>
                    <td>Project Manager</td>
                    <td>john.doe@example.com</td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-info">Message</button>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://via.placeholder.com/50" class="rounded-circle" alt="Jane Smith"></td>
                    <td>Jane Smith</td>
                    <td>Developer</td>
                    <td>jane.smith@example.com</td>
                    <td><span class="badge bg-warning text-dark">On Leave</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-info">Message</button>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://via.placeholder.com/50" class="rounded-circle" alt="Michael Brown"></td>
                    <td>Michael Brown</td>
                    <td>Designer</td>
                    <td>michael.brown@example.com</td>
                    <td><span class="badge bg-secondary">Inactive</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-info">Message</button>
                    </td>
                </tr>
                <!-- More team members -->
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript for search and status filter -->
<script>
const searchInput = document.getElementById('teamSearch');
const statusSelect = document.getElementById('statusFilter');
const tableRows = document.querySelectorAll('#teamTableBody tr');

function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusTerm = statusSelect.value.toLowerCase();

    tableRows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const role = row.cells[2].textContent.toLowerCase();
        const status = row.cells[4].textContent.toLowerCase();

        const matchesSearch = name.includes(searchTerm) || role.includes(searchTerm);
        const matchesStatus = statusTerm === "" || status.includes(statusTerm);

        row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
    });
}

searchInput.addEventListener('keyup', filterTable);
statusSelect.addEventListener('change', filterTable);
</script>


            <!-- Modal: Add/Edit Task -->
            <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_id" id="task_id">
                            <input type="hidden" name="project_id" value="#">
                            <div class="modal-header">
                                <h5 class="modal-title" id="taskModalLabel">+ Add/Edit Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Task Name</label>
                                    <input type="text" name="name" id="task_name" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Assignee</label>
                                    <select name="assignee_id" id="assignee_id" class="form-select" required>
                                        <option value="">Select Assignee</option>
                                        <option value="1">Jane Smith</option>
                                        <option value="2">Michael Brown</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deadline</label>
                                    <input type="date" name="deadline" id="deadline" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Progress (%)</label>
                                    <input type="number" name="progress" id="progress" class="form-control"
                                        min="0" max="100" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="completed">Completed</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark">Save Task</button>
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Optional: JS to populate modal for Edit -->
            <script>
                const taskModal = document.getElementById('taskModal');
                taskModal.addEventListener('show.bs.modal', event => {
                    const button = event.relatedTarget;
                    // If editing, populate fields
                    const row = button.closest('tr');
                    if (row) {
                        const taskId = row.dataset.id;
                        const taskName = row.cells[0].innerText;
                        const assignee = row.cells[1].innerText;
                        const startDate = row.cells[2].innerText;
                        const deadline = row.cells[3].innerText;
                        const progress = row.cells[4].querySelector('.progress-bar').style.width.replace('%', '');
                        const status = row.cells[5].innerText.toLowerCase();

                        taskModal.querySelector('#task_id').value = taskId || '';
                        taskModal.querySelector('#task_name').value = taskName;
                        taskModal.querySelector('#assignee_id').value = assignee === 'Jane Smith' ? 1 :
                        2; // example mapping
                        taskModal.querySelector('#start_date').value = startDate;
                        taskModal.querySelector('#deadline').value = deadline;
                        taskModal.querySelector('#progress').value = progress;
                        taskModal.querySelector('#status').value = status;
                    }
                });
            </script>




        </div> <!-- End container-fluid -->

        @include('layouts.js')
    </div>

    <!-- Custom CSS -->
    <style>
        .dashboard-padding {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }

        .small-chart {
            max-width: 250px;
            max-height: 250px;
            margin: auto;
            display: block;
        }
    </style>

    <!-- Sample Chart.js scripts -->
    <script>
        const ctxTasksStatus = document.getElementById('tasksStatusChart').getContext('2d');
        new Chart(ctxTasksStatus, {
            type: 'pie',
            data: {
                labels: ['Completed', 'In Progress', 'Pending'],
                datasets: [{
                    data: [5, 3, 2],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const ctxProgressTrend = document.getElementById('progressTrendChart').getContext('2d');
        new Chart(ctxProgressTrend, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Progress %',
                    data: [20, 45, 65, 80],
                    fill: false,
                    borderColor: '#0d6efd',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

</body>

</html>
