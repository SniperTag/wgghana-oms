<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Livewire\FaceEnrollment; // Assuming you have a Livewire component for face enrollment
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Events\StaffStepEvent;
use App\Notifications\StaffStatusAlert;
use Illuminate\Support\Facades\Hash;

class FaceEnrollmentController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // Check if the user has a face image enrolled
        if ($user->face_image) {
            return redirect()->route('dashboard')->with('message', 'Face already enrolled.');
        }
        return view('user.face-enrollment');
    }

     protected $rules = [
        'face_image' => 'required|string',
    ];
     public function saveFaceImage()
    {
        $validatedData = request()->validate($this->rules);

        $user = Auth::user();
        if ($user->face_image) {
        session()->flash('error', 'Face already enrolled. Please contact admin to update.');
        return redirect()->route('dashboard');
    }

        // Save base64 string or optionally save file and store path
        $user->face_image = $validatedData['faceImage'];
        $user->save();

        session()->flash('success', 'Face enrolled successfully!');

        return redirect()->back();
    }
}
