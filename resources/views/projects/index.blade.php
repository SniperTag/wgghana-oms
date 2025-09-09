<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app')

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
            @if (session('success'))
                <div class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-5 zindex-modal"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">📂 Projects</h2>
                <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                    + New Project
                </button>
            </div>

            <!-- KPI Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Total Projects</div>
                        <div class="fs-4 fw-bold">120</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Active Projects</div>
                        <div class="fs-4 fw-bold text-primary">87</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Completed Projects</div>
                        <div class="fs-4 fw-bold text-success">62</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center shadow-sm">
                        <div class="text-muted">Overdue Projects</div>
                        <div class="fs-4 fw-bold text-danger">14</div>
                    </div>
                </div>
            </div>



            <!-- Project Table -->
            <div class="card p-3 shadow-sm mb-4">
    <h6 class="fw-bold mb-2">Project List</h6>
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Project Name</th>
                    <th>Manager</th>
                    <th>Start Date</th>
                    <th>Deadline</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->manager->name ?? 'N/A' }}</td>
                        <td>{{ $project->start_date->format('d M Y') }}</td>
                        <td>{{ $project->end_date->format('d M Y') }}</td>
                        <td>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar 
                                    @if($project->status == 'completed') bg-success
                                    @elseif($project->status == 'on_hold') bg-warning
                                    @elseif($project->status == 'cancelled') bg-danger
                                    @else bg-primary @endif" 
                                    style="width: {{ $project->progress ?? 0 }}%">
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                @if($project->status == 'completed') bg-success
                                @elseif($project->status == 'on_hold') bg-warning text-dark
                                @elseif($project->status == 'cancelled') bg-danger
                                @else bg-primary @endif">
                                {{ ucfirst(str_replace('_',' ',$project->status)) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info">View</button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editProjectModal-{{ $project->id }}">Edit</button>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $projects->links() }}
        </div>
    </div>
</div>


        </div> <!-- End container-fluid -->

        @include('layouts.js')
    </div>

    <!-- Modal: Create New Project -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProjectModalLabel">+ New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Project Title -->
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- Project Manager -->
                        <div class="mb-3">
                            <label class="form-label">Project Manager</label>
                            <input type="text" id="manager_name" value="{{ $currentUser->name }}"
                                class="form-control" readonly>
                            <input type="hidden" name="manager_id" value="{{ $currentUser->id }}">
                        </div>

                        <!-- Start Date -->
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>

                        <!-- End Date / Deadline -->
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>

                        <!-- Attachment -->
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                            <small class="text-muted">Allowed: pdf, doc, docx, xls, xlsx, png, jpg, jpeg</small>
                        </div>

                        <!-- Status Comment -->
                        <div class="mb-3">
                            <label class="form-label">Status Comment</label>
                            <textarea name="status_comment" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Color -->
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" class="form-control form-control-color"
                                value="#007bff">
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label class="form-label">Tags (comma-separated)</label>
                            <input type="text" name="tags" class="form-control"
                                placeholder="e.g., ERP, Finance, Urgent">
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



    <!-- Edit Project Modal -->
    {{-- <div class="modal fade" id="editProjectModal-{{ $projectss->id }}" tabindex="-1"
        aria-labelledby="editProjectLabel-{{ $projectss->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('projects.update', $projectss->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editProjectLabel-{{ $projectss->id }}">Edit Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Project Title -->
                        <div class="mb-3">
                            <label for="title-{{ $projectss->id }}" class="form-label">Project Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                name="title" id="title-{{ $projectss->id }}"
                                value="{{ old('title', $projectss->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description-{{ $projectss->id }}" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                                id="description-{{ $projectss->id }}" rows="3">{{ old('description', $projectss->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div class="mb-3">
                            <label for="start_date-{{ $projectss->id }}" class="form-label">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                name="start_date" id="start_date-{{ $projectss->id }}"
                                value="{{ old('start_date', $projectss->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="mb-3">
                            <label for="end_date-{{ $projectss->id }}" class="form-label">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                name="end_date" id="end_date-{{ $projectss->id }}"
                                value="{{ old('end_date', $projectss->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Manager (read-only) -->
                        <div class="mb-3">
                            <label class="form-label">Project Manager</label>
                            <input type="text" class="form-control" value="{{ $projectss->manager->name }}"
                                readonly>
                            <input type="hidden" name="manager_id" value="{{ $projectss->manager_id }}">
                        </div>

                        <!-- Department (read-only) -->
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="{{ $projectss->department->name }}"
                                readonly>
                            <input type="hidden" name="department_id" value="{{ $projectss->department_id }}">
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status-{{ $projectss->id }}" class="form-label">Status</label>
                            <select name="status" id="status-{{ $projectss->id }}"
                                class="form-select @error('status') is-invalid @enderror">
                                @foreach (['pending', 'in-progress', 'completed', 'on-hold', 'cancelled'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status', $projectss->status) === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label for="priority-{{ $projectss->id }}" class="form-label">Priority</label>
                            <select name="priority" id="priority-{{ $projectss->id }}"
                                class="form-select @error('priority') is-invalid @enderror">
                                @foreach (['low', 'medium', 'high', 'critical'] as $priority)
                                    <option value="{{ $priority }}"
                                        {{ old('priority', $projects->priority) === $priority ? 'selected' : '' }}>
                                        {{ ucfirst($priority) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="mb-3">
                            <label for="attachment-{{ $projects->id }}" class="form-label">Attachment</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                name="attachment" id="attachment-{{ $projects->id }}">
                            @if ($projects->attachment)
                                <small class="text-muted">Current: <a
                                        href="{{ asset('storage/' . $projects->attachment) }}" target="_blank">View
                                        file</a></small>
                            @endif
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label for="tags-{{ $projects->id }}" class="form-label">Tags (comma-separated)</label>
                            <input type="text" name="tags" id="tags-{{ $projects->id }}" class="form-control"
                                value="{{ old('tags', is_array(json_decode($projects->tags, true)) ? implode(',', json_decode($projects->tags, true)) : $projects->tags) }}">
                        </div>

                        <!-- Status Comment -->
                        <div class="mb-3">
                            <label for="status_comment-{{ $projects->id }}" class="form-label">Status Comment</label>
                            <textarea name="status_comment" id="status_comment-{{ $projects->id }}" class="form-control" rows="2">{{ old('status_comment', $projects->status_comment) }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Project</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Pagination links -->
        <div class="mt-3">
            {{ $projects->links() }}
        </div>
    </div> --}}


    <!-- Custom CSS -->
    <style>
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

</body>

</html>
