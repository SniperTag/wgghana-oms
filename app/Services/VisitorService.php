<?php

namespace App\Services;

use App\Models\Visitor;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class VisitorService
{
    public function createVisitor(array $data): Visitor
    {
        // Handle file uploads
        if (isset($data['photo']) && $data['photo'] instanceof TemporaryUploadedFile) {
            $data['photo'] = $data['photo']->store('visitor-photos', 'public');
        } else {
            unset($data['photo']);
        }

        if (isset($data['signature']) && $data['signature'] instanceof TemporaryUploadedFile) {
            $data['signature'] = $data['signature']->store('visitor-signatures', 'public');
        } else {
            unset($data['signature']);
        }

        // Create visitor
        $visitor = Visitor::create($data);

        // Generate and update UID
        $visitor->update([
            'visitor_uid' => $this->generateVisitorUid($visitor->id)
        ]);

        return $visitor->fresh();
    }

    public function generateVisitorUid(int $visitorId): string
    {
        return 'WG-VST-' . str_pad($visitorId, 4, '0', STR_PAD_LEFT) . '-' . now()->format('Y');
    }

    public function generateGroupUid(): string
    {
        return 'WG-GRP-' . strtoupper(uniqid()) . '-' . now()->format('Y');
    }

    public function updateVisitor(Visitor $visitor, array $data): Visitor
    {
        // Handle file uploads
        if (isset($data['photo']) && $data['photo'] instanceof TemporaryUploadedFile) {
            // Delete old photo if exists
            if ($visitor->photo) {
                Storage::disk('public')->delete($visitor->photo);
            }
            $data['photo'] = $data['photo']->store('visitor-photos', 'public');
        }

        if (isset($data['signature']) && $data['signature'] instanceof TemporaryUploadedFile) {
            // Delete old signature if exists
            if ($visitor->signature) {
                Storage::disk('public')->delete($visitor->signature);
            }
            $data['signature'] = $data['signature']->store('visitor-signatures', 'public');
        }

        $visitor->update($data);

        return $visitor->fresh();
    }

    public function deleteVisitor(Visitor $visitor): bool
    {
        // Delete associated files
        if ($visitor->photo) {
            Storage::disk('public')->delete($visitor->photo);
        }

        if ($visitor->signature) {
            Storage::disk('public')->delete($visitor->signature);
        }

        return $visitor->delete();
    }

    public function getVisitorsByGroup(string $groupUid)
    {
        return Visitor::where('group_uid', $groupUid)
            ->orderBy('is_leader', 'desc')
            ->orderBy('created_at')
            ->get();
    }

    public function getGroupLeader(string $groupUid): ?Visitor
    {
        return Visitor::where('group_uid', $groupUid)
            ->where('is_leader', true)
            ->first();
    }
}