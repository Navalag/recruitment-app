<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;

class TestTasksController extends Controller
{
    public function startTestTask($key)
    {
        $applicant = Applicant::where('start_test_link', 'LIKE', '%'.$key)->first();
        if ($applicant) {
            $applicant->start_test_time = now();
            $applicant->save();
        }

        return redirect('/');
    }

    public function finishTestTask($key)
    {
        $applicant = Applicant::where('finish_test_link', 'LIKE', '%'.$key)->first();
        if ($applicant) {
            $applicant->finish_test_time = now();
            $applicant->save();
        }

        return redirect('/');
    }
}
