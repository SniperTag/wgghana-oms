<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class FaceVerificationService
{
    /**
     * Compares a submitted face snapshot to the stored face image.
     * @param string $submittedBase64
     * @param string $storedBase64
     * @return bool
     */
    public function verifyFace(string $submittedBase64, string $storedBase64): bool
    {
        // TODO: Replace this placeholder logic with actual face recognition engine/API call
        if ($submittedBase64 === $storedBase64) {
            return true; // perfect match (for testing)
        }

        // Simulate confidence check
        $similarity = $this->simulateFaceMatch($submittedBase64, $storedBase64);
        Log::info("🧠 Face match confidence: {$similarity}");

        return $similarity >= 0.8;
    }

    /**
     * Simulate face match logic (replace with real logic or API).
     */
    private function simulateFaceMatch(string $face1, string $face2): float
    {
        // Simulate a comparison score (you can use cosine similarity, ML API, etc.)
        return rand(80, 100) / 100; // e.g. 0.80 - 1.00
    }
}
