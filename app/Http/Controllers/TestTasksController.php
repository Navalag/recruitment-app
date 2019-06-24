<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Exception;

class TestTasksController extends Controller
{
    /**
     * Show start task view.
     *
     * @param $key
     * @return \Illuminate\View\View
     */
    public function startTestTask($key)
    {
        $applicant = Applicant::where('unique_key', 'LIKE', '%'.$key)->first();

        if (! $applicant) return redirect('/');

        return view('test-task.start', compact('applicant'));
    }

    /**
     * Start time counting.
     *
     * @param $key
     * @return \Illuminate\View\View
     */
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

    /**
     * Show finish task view.
     *
     * @param $key
     * @return \Illuminate\View\View
     */
    public function finishTestTask($key)
    {
        $applicant = Applicant::where('unique_key', 'LIKE', '%'.$key)->first();

        if (! $applicant) return redirect('/');

        return view('test-task.finish', compact('applicant'));
    }

    /**
     * Stop time counting.
     *
     * @param Request $request
     * @return Redirect
     *
     * @throws Exception
     */
    public function recordFinishTestTaskTime(Request $request)
    {
        $this->validate($request, [
            'email'      => 'email|required|max:50',
            'link'       => 'string|required|max:50',
        ]);

        $applicant = Applicant::where('email', $request->get('email'))->first();

        if (! $applicant) return redirect('/');

        if (! $applicant->start_test_time) {
            // TODO: handel this case smarter
            $applicant->start_test_time = now();
        }

        if (! $applicant->finish_test_time) {
            $applicant->finish_test_time = now();
            $applicant->test_task_link = $request->get('link');
            $applicant->status = 'test finished';
            $applicant->save();
        }

        return redirect('/');
    }
}
