<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\ProjectService; // make sure you have this service

class ProjectController extends Controller
{
    public function tasksIndex()
    {
        return view('projects.tasks-index');
    }

    public function dashboard()
    {
        return view('projects.dashboard');
    }

    public function create()
    {
        $currentUser = Auth::user();
          $projects = Project::with('manager')->paginate(10);
        return view('projects.index', compact('currentUser','projects'));
    }

    public function store(Request $request, ProjectService $projectService)
    {
        try {
            // ✅ Validate inputs
            $validated = $request->validate([
                'title'          => 'required|string|max:255',
                'description'    => 'nullable|string',
                'start_date'     => 'required|date',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'status'         => 'required|in:active,completed,on_hold,cancelled',
                'priority'       => 'nullable|in:low,medium,high,critical',
                'attachment'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:5120',
                'status_comment' => 'nullable|string|max:500',
                'color'          => 'nullable|string|max:20',
                'tags'           => 'nullable|string|max:255',
            ]);

            // ✅ Auto-assign manager, department, and created_by
            $validated['manager_id']    = Auth::id();
            $validated['department_id'] = Auth::user()->department_id;
            $validated['created_by']    = Auth::id();
            $validated['ip_address']    = $request->ip();
            $validated['user_agent']    = $request->userAgent();

            // ✅ Save project via service
            $project = $projectService->create($validated);

            session()->flash('success', 'Project Created Successfully');
            return redirect()
                ->back();

        } catch (\Throwable $e) {
            // Log error for debugging
            \Log::error('Project creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the project. Please try again.');
        }
    }


    public function edit(Project $project)
{
    // Only show the project if the user is allowed
    $currentUser = auth()->user();

    // You can add authorization logic here if needed
    return view('projects.edit', compact('project', 'currentUser'));
}

public function update(Request $request, Project $project, ProjectService $projectService)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'status'         => 'required|in:active,completed,on_hold,cancelled',
            'priority'       => 'nullable|in:low,medium,high,critical',
            'attachment'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:5120',
            'status_comment' => 'nullable|string|max:500',
            'color'          => 'nullable|string|max:20',
            'tags'           => 'nullable|string|max:255',
        ]);

        // Auto-update metadata
        $validated['updated_by'] = auth()->id();
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        // If a new attachment is uploaded, handle it
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('projects/attachments', 'public');
        }

        // Update the project via the service
        $projectService->update($project, $validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project updated successfully!');

    } catch (\Throwable $e) {
        \Log::error('Project update failed: '.$e->getMessage(), [
            'project_id' => $project->id,
            'trace' => $e->getTraceAsString()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Something went wrong while updating the project. Please try again.');
    }
}

}
