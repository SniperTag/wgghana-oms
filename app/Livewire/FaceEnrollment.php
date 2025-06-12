<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FaceEnrollment extends Component
{
    public $faceImage;

    // Listen for event from JS when webcam captures base64 image
    protected $listeners = ['imageCaptured' => 'setFaceImage'];

    public function setFaceImage($image)
    {
        $this->faceImage = $image;
    }

    public function saveFaceImage()
    {
        $user = Auth::user();

        if ($user->face_image) {
            session()->flash('error', 'Face already enrolled.');
            return redirect()->route('dashboard');
        }

        // Extract base64 string
        $base64String = preg_replace('/^data:image\/\w+;base64,/', '', $this->faceImage);
        $image = base64_decode($base64String);

        if (!$image) {
            session()->flash('error', 'Invalid image data.');
            return;
        }

        // Generate unique filename
        $filename = 'face_' . $user->id . '_' . Str::random(10) . '.png';

        // Save to disk (public/face_images)
        Storage::disk('public')->put("face_images/{$filename}", $image);

        // Save relative path in DB
        $user->face_image = "face_images/{$filename}";
        $user->save();

        session()->flash('success', 'Face enrolled successfully!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.face-enrollment');
    }
}
