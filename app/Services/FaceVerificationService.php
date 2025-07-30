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


// namespace App\Services;

// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Http;

// class FaceVerificationService
// {
//     /**
//      * Compares a submitted face snapshot to the stored face image via external API.
//      * @param string $submittedBase64
//      * @param string $storedBase64
//      * @return bool
//      */
//     public function verifyFace(string $submittedBase64, string $storedBase64): bool
//     {
//         try {
//             $response = Http::timeout(10)->post('http://192.168.100.65:5000/compare-faces', [
//                 'live_image' => $submittedBase64,
//                 'stored_image' => $storedBase64,
//             ]);

//             if ($response->successful()) {
//                 $json = $response->json();
//                 Log::info("🧠 Face verification response:", $json);

//                 return isset($json['verified']) && $json['verified'] === true;
//             } else {
//                 Log::error('Face verification API returned error: ' . $response->body());
//             }
//         } catch (\Exception $e) {
//             Log::error('Face verification API request failed: ' . $e->getMessage());
//         }

//         return false;
//     }
// }

