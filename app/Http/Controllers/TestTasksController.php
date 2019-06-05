<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;

class TestTasksController extends Controller
{
    public function startTestTask($key)
    {
        $applicant = Applicant::where('unique_key', 'LIKE', '%'.$key)->first();

        if (! $applicant) return redirect('/');

        return view('test-task.start', compact('applicant'));
    }

    public function beginTestTask(Request $request)
    {
        $applicant = Applicant::where('email', $request->get('email'))->first();

        if (! $applicant) return redirect('/');

        if (! $applicant->start_test_time) {
            $applicant->start_test_time = now();
            $applicant->status = 'test started';
            $applicant->save();
        }

        return redirect($applicant->jobAppliedFor->test_task_url);
    }

    public function finishTestTask($key)
    {
        $applicant = Applicant::where('unique_key', 'LIKE', '%'.$key)->first();

        if (! $applicant) return redirect('/');

        return view('test-task.finish', compact('applicant'));
    }

    public function recordFinishTestTaskTime(Request $request)
    {
        $this->validate($request, [
            'email'      => 'email|required|max:50',
            'link'       => 'string|required|max:50',
        ]);

        $applicant = Applicant::where('email', $request->get('email'))->first();

        if (! $applicant) return redirect('/');

        if (! $applicant->finish_test_time) {
            $applicant->finish_test_time = now();
            $applicant->status = 'test finished';
            $applicant->save();
        }

        return redirect('/');
    }
}
