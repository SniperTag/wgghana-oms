<!DOCTYPE html>
<html lang="en">
<head>
   @include('layouts.app')
    <title>Self Assessment</title>
    <style>
        .dashboard-padding {
            padding-top: 6rem;
            padding-bottom: 6rem;
            padding-right: 6rem;
            padding-right: 6rem;
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

                <div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Waltergates Staff Weekly Work Assessment Form</h5>
    <small>Complete every Friday before 5:00 PM</small>
  </div>
  <div class="card-body">
    <form>
      <!-- SECTION 1: Personal Information -->
      <h6 class="fw-bold mt-3">Section 1: Personal Information</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" placeholder="Enter your name">
        </div>
        <div class="col-md-6">
          <label class="form-label">Staff ID</label>
          <input type="text" class="form-control" placeholder="WG-1234">
        </div>
        <div class="col-md-6">
          <label class="form-label">Department/Unit</label>
          <select class="form-select">
            <option>IT & Systems</option>
            <option>Business Development</option>
            <option>Accounts & Admin</option>
            <option>Admin & HR</option>
            <option>Finance</option>
            <option>Sales & Partnerships</option>
            <option>Other</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Week Ending</label>
          <input type="date" class="form-control">
        </div>
      </div>

      <!-- SECTION 2: Weekly Work Plan vs Output -->
      <h6 class="fw-bold mt-4">Section 2: Weekly Work Plan vs Output</h6>
      <div class="mb-3">
        <label class="form-label">Did you have a work plan/task list this week?</label>
        <select class="form-select">
          <option>Yes</option>
          <option>No</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Major assigned tasks</label>
        <textarea class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Tasks completed this week</label>
        <textarea class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Uncompleted tasks (if any)</label>
        <textarea class="form-control" rows="2"></textarea>
      </div>

      <!-- SECTION 3: Output & Deliverables -->
      <h6 class="fw-bold mt-4">Section 3: Output & Deliverables</h6>
      <div class="mb-3">
        <label class="form-label">Total number of tasks completed</label>
        <input type="number" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Major deliverables submitted</label>
        <textarea class="form-control" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Innovations/ideas contributed</label>
        <textarea class="form-control" rows="2"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Did work align with department goals?</label>
        <select class="form-select">
          <option>Yes</option>
          <option>No</option>
        </select>
      </div>

      <!-- SECTION 4: Time & Efficiency -->
      <h6 class="fw-bold mt-4">Section 4: Time & Efficiency</h6>
      <div class="mb-3">
        <label class="form-label">Average hours worked per day</label>
        <select class="form-select">
          <option>Less than 4 hours</option>
          <option>4–6 hours</option>
          <option>6–8 hours</option>
          <option>More than 8 hours</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Unproductive periods (explain if any)</label>
        <textarea class="form-control" rows="2"></textarea>
      </div>

      <!-- SECTION 5: Collaboration & Communication -->
      <h6 class="fw-bold mt-4">Section 5: Collaboration & Communication</h6>
      <div class="mb-3">
        <label class="form-label">Collaborated with (team/department)</label>
        <input type="text" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Rate your communication this week (1–5)</label>
        <input type="range" class="form-range" min="1" max="5">
      </div>

      <!-- SECTION 6: Challenges & Support -->
      <h6 class="fw-bold mt-4">Section 6: Challenges & Support Needed</h6>
      <div class="mb-3">
        <label class="form-label">Challenges faced this week</label>
        <textarea class="form-control" rows="2"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Support needed from management</label>
        <textarea class="form-control" rows="2"></textarea>
      </div>

      <!-- SECTION 7: Self-Assessment -->
      <h6 class="fw-bold mt-4">Section 7: Self-Assessment & Rating</h6>
      <div class="mb-3">
        <label class="form-label">Self-performance rating (1–10)</label>
        <input type="range" class="form-range" min="1" max="10">
      </div>
      <div class="mb-3">
        <label class="form-label">Justify your rating</label>
        <textarea class="form-control" rows="2"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Do you feel you earned your full salary?</label>
        <select class="form-select">
          <option>Yes</option>
          <option>No</option>
          <option>Partially</option>
        </select>
      </div>

      <!-- Submit -->
      <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary">Submit Assessment</button>
      </div>
    </form>
  </div>
</div>


            </div>
            @include('layouts.js')

    </div>
    
</body>
</html>