<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ProjectService
{
    /**
     * Create a new project with optional file upload.
     *
     * @param array $data
     * @return \App\Models\Project
     */
    public function create(array $data): Project
    {
        // Handle file upload if attachment is provided
        if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
            $data['attachment'] = $data['attachment']->store('projects/attachments', 'public');
        }

        $project = Project::create([
            'title'          => $data['title'],
            'description'    => $data['description'] ?? null,
            'department_id'  => $data['department_id'] ?? auth()->user()->department_id,
            'manager_id'     => $data['manager_id'] ?? auth()->id(), // always a user_id
            'created_by'     => auth()->id(),
            'status'         => $data['status'] ?? 'pending',
            'start_date'     => $data['start_date'] ?? null,
            'end_date'       => $data['end_date'] ?? null,
            'notes'          => $data['notes'] ?? null,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->header('User-Agent'),
            'attachment'     => $data['attachment'] ?? null,
            'status_comment' => $data['status_comment'] ?? null,
            'priority'       => $data['priority'] ?? 'medium',
            'color'          => $data['color'] ?? '#007bff',
            'tags'           => isset($data['tags']) ? json_encode($data['tags']) : null,
            'attachment'  => $data['attachment'] ?? null,
        ]);

        // Log creation event for auditing
        Log::info("Project created successfully", [
            'project_id' => $project->id,
            'manager_id' => $project->manager_id,
            'created_by' => auth()->id(),
        ]);

        return $project;
    }


    public function update(Project $project, array $data): Project
{
    // Handle file upload if new attachment exists
    if (isset($data['attachment']) && $data['attachment'] instanceof \Illuminate\Http\UploadedFile) {
        $data['attachment'] = $data['attachment']->store('projects/attachments', 'public');
    }

    $project->update($data);

    \Log::info('Project updated', [
        'project_id' => $project->id,
        'updated_by' => auth()->id(),
    ]);

    return $project;
}

}
