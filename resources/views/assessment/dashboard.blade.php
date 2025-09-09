<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app')
    <title>Assessment Dashboard</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 0.75rem;
        }

        .table thead th {
            vertical-align: middle;
        }

        .chart-container {
            min-height: 300px;
        }

        .dashboard-padding {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }

        /* Make modal backdrop transparent */
        .modal-backdrop.show {
            background-color: transparent;
        }

        /* Optional: Remove modal dialog background */
        .modal-content {
            background-color: rgba(255, 255, 255, 0.9);
            /* slightly transparent white */
            border: none;
            /* remove border if needed */
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

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">📝 Assessment Dashboard</h2>
                <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createAssessmentModal">
                    + New Assessment
                </button>

            </div>

            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Total Assessments</div>
                        <div class="fs-4 fw-bold">220</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Completed</div>
                        <div class="fs-4 fw-bold text-success">180</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Pending</div>
                        <div class="fs-4 fw-bold text-warning">30</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Average Score</div>
                        <div class="fs-4 fw-bold text-primary">78%</div>
                    </div>
                </div>
            </div>
            {{-- performance Highlight --}}

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="text-muted">Avg Weekly Rating</div>
                        <div class="fs-4 fw-bold text-primary">7.8</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="text-muted">Highest Performer</div>
                        <div class="fs-6 fw-bold text-success">Daniel Nelson</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="text-muted">Lowest Performer</div>
                        <div class="fs-6 fw-bold text-danger">John Smith</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="text-muted">Total Tasks Completed</div>
                        <div class="fs-4 fw-bold">125</div>
                    </div>
                </div>
            </div>
{{-- Department Performance --}}

<div class="row g-3 mb-4">
  <!-- Department Selection -->
  <div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold">Department Performance Overview</h6>
      <select class="form-select w-auto">
        <option selected>All Departments</option>
        <option>Human Resources</option>
        <option>Finance</option>
        <option>IT</option>
        <option>Marketing</option>
        <option>Operations</option>
      </select>
    </div>
  </div>

  <!-- Department Metrics -->
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <div class="text-muted">Avg Weekly Rating</div>
      <div class="fs-4 fw-bold text-primary">8.2</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <div class="text-muted">Top Performer</div>
      <div class="fs-6 fw-bold text-success">Mary Johnson</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <div class="text-muted">Lowest Performer</div>
      <div class="fs-6 fw-bold text-danger">David Lee</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <div class="text-muted">Tasks Completed</div>
      <div class="fs-4 fw-bold">86</div>
    </div>
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="fw-bold mb-0">Department Performance Comparison</h6>
    <select class="form-select w-auto">
      <option selected>This Month</option>
      <option>Last Month</option>
      <option>Last 3 Months</option>
      <option>Yearly</option>
    </select>
  </div>
  <div class="card-body">
    <canvas id="deptPerformanceChart" height="100"></canvas>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('deptPerformanceChart').getContext('2d');
  const deptPerformanceChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['HR', 'Finance', 'IT', 'Marketing', 'Operations'],
      datasets: [{
        label: 'Average Rating',
        data: [7.5, 8.2, 6.9, 7.8, 8.5], // Example ratings
        backgroundColor: [
          'rgba(54, 162, 235, 0.7)',
          'rgba(75, 192, 192, 0.7)',
          'rgba(255, 159, 64, 0.7)',
          'rgba(153, 102, 255, 0.7)',
          'rgba(255, 99, 132, 0.7)'
        ],
        borderRadius: 8
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: { mode: 'index', intersect: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 10,
          ticks: { stepSize: 1 }
        }
      }
    }
  });
</script>



            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">📈 Weekly Average Performance Trend (All Staff)</h6>
                </div>
                <div class="card-body">
                    <canvas id="allStaffTrend" height="120"></canvas>
                </div>
            </div>

            <script>
                new Chart(document.getElementById("allStaffTrend"), {
                    type: 'line',
                    data: {
                        labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
                        datasets: [{
                            label: "Average Rating",
                            data: [7.4, 8.1, 7.8, 8.3],
                            borderColor: "#0d6efd",
                            backgroundColor: "rgba(13, 110, 253, 0.1)",
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 10,
                                stepSize: 1
                            }
                        }
                    }
                });
            </script>


            <!-- Charts -->
            <div class="row g-3 mb-4">
                <div class="col-lg-6">
                    <div class="card p-3 shadow-sm chart-container">
                        <h6 class="fw-bold mb-3">Assessment Completion</h6>
                        <canvas id="completionChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-3 shadow-sm chart-container">
                        <h6 class="fw-bold mb-3">Score Distribution</h6>
                        <canvas id="scoreChart"></canvas>
                    </div>
                </div>
            </div>



            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">📊 Staff Weekly Performance Trail</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Staff</th>
                                    <th>Department</th>
                                    <th>Tasks Completed</th>
                                    <th>Rating (1–10)</th>
                                    <th>Status</th>
                                    <th>Week Ending</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Daniel Nelson</td>
                                    <td>IT & Systems</td>
                                    <td>12</td>
                                    <td><span class="badge bg-success">9</span></td>
                                    <td>Excellent</td>
                                    <td>30 Aug 2025</td>
                                </tr>
                                <tr>
                                    <td>Ama K.</td>
                                    <td>Sales & Partnerships</td>
                                    <td>8</td>
                                    <td><span class="badge bg-warning text-dark">6</span></td>
                                    <td>Average</td>
                                    <td>30 Aug 2025</td>
                                </tr>
                                <tr>
                                    <td>John Smith</td>
                                    <td>Finance</td>
                                    <td>5</td>
                                    <td><span class="badge bg-danger">4</span></td>
                                    <td>Below Expectation</td>
                                    <td>30 Aug 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Assessments -->
            <!-- Recent Assessments -->
            <div class="card p-3 shadow-sm">
                <h6 class="fw-bold mb-3">Recent Assessments</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Staff</th>
                                <th>Assessment</th>
                                <th>Score</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Daniel Nelson</td>
                                <td>Q3 Review</td>
                                <td>87%</td>
                                <td><span class="badge bg-success">Good</span></td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>01 Sep 2025</td>
                            </tr>
                            <tr>
                                <td>Ama K.</td>
                                <td>Safety Training</td>
                                <td>-</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>02 Sep 2025</td>
                            </tr>
                            <tr>
                                <td>John Smith</td>
                                <td>Leadership Training</td>
                                <td>72%</td>
                                <td><span class="badge bg-warning text-dark">Average</span></td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>28 Aug 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @include('layouts.js')
        </div>

        <script>
            // Assessment Completion Chart
            new Chart(document.getElementById("completionChart"), {
                type: 'bar',
                data: {
                    labels: ["Completed", "Pending"],
                    datasets: [{
                        label: 'Assessments',
                        data: [180, 30],
                        backgroundColor: ["#198754", "#ffc107"]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    }
                }
            });

            // Score Distribution Chart
            new Chart(document.getElementById("scoreChart"), {
                type: 'pie',
                data: {
                    labels: ["Excellent (90%+)", "Good (75-89%)", "Average (60-74%)", "Poor (<60%)"],
                    datasets: [{
                        data: [45, 80, 50, 25],
                        backgroundColor: ["#0d6efd", "#20c997", "#ffc107", "#dc3545"]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        </script>
    </div>

    <!-- Modal: Create New Assessment -->
    <div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('assessments.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAssessmentModalLabel">+ New Assessment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <!-- Staff Selection -->
                            <div class="col-md-6">
                                <label for="staff_id" class="form-label">Staff</label>
                                <select name="staff_id" id="staff_id" class="form-select" required>
                                    <option value="">Select Staff</option>
                                    <option value="1">Daniel Nelson</option>
                                    <option value="2">Ama K.</option>
                                    <option value="3">John Smith</option>
                                </select>
                            </div>

                            <!-- Assessment Name -->
                            <div class="col-md-6">
                                <label for="assessment_name" class="form-label">Assessment Name</label>
                                <input type="text" name="name" id="assessment_name" class="form-control"
                                    placeholder="e.g., Q4 Review" required>
                            </div>

                            <!-- Assessment Type -->
                            <div class="col-md-6">
                                <label for="assessment_type" class="form-label">Assessment Type</label>
                                <select name="type" id="assessment_type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="performance">Performance</option>
                                    <option value="training">Training</option>
                                    <option value="safety">Safety</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <!-- Score -->
                            <div class="col-md-6">
                                <label for="score" class="form-label">Score (%)</label>
                                <input type="number" name="score" id="score" class="form-control"
                                    min="0" max="100" placeholder="0-100">
                            </div>

                            <!-- Assessment Rating -->
                            <div class="col-md-6">
                                <label for="rating" class="form-label">Assessment Rating</label>
                                <select name="rating" id="rating" class="form-select" required>
                                    <option value="">Select Rating</option>
                                    <option value="excellent">Excellent (90%+)</option>
                                    <option value="good">Good (75-89%)</option>
                                    <option value="average">Average (60-74%)</option>
                                    <option value="poor">Poor (<60%) </option>
                                </select>
                            </div>




                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes / Comments</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Create Assessment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>
