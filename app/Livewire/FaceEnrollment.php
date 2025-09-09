<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FaceEnrollment extends Component
{
    protected $listeners = ['imageCaptured'];

    public function imageCaptured($dataUrl)
    {
        $user = Auth::user();

        if ($user->face_image) {
            session()->flash('error', 'Face already enrolled.');
            return redirect()->route('dashboard');
        }

        // Remove "data:image/jpeg;base64," prefix
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
        $image = str_replace(' ', '+', $image);
        $filename = 'face_' . $user->id . '_' . time() . '.jpg';

        Storage::disk('public')->put('face_images/' . $filename, base64_decode($image));

        $user->face_image = 'face_images/' . $filename;
        $user->save();

        session()->flash('success', 'Face enrolled successfully!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.face-enrollment');
    }
}
