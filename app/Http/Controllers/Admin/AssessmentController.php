<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function asdashboard(){
        return view('assessment.dashboard');
    }

    //Self Assessment function
    public function selfAss(){
        return view('assessment.self-assess');
    }
}
