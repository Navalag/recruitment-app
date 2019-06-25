<?php

namespace App\Http\Controllers\TestTasks;

use App\Applicant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\View\View;

class StartTestTaskController extends Controller
{
    /**
     * Show start task view.
     *
     * @param $key
     * @return View
     */
    public function index($key)
    {
        $applicant = Applicant::where('unique_key', 'LIKE', '%'.$key)->first();

        if (! $applicant) return redirect('/');

        return view('test-task.start', compact('applicant'));
    }

    /**
     * Start time counting.
     *
     * @param Request $request
     * @return View
     *
     * @throws Exception
     */
    public function beginTask(Request $request)
    {
        $this->validate($request, [
            'email'      => 'required|email|max:50',
            'unique_key' => 'required|string|max:255'
        ]);

        $applicant = Applicant::where('email', $request->get('email'))
            ->where('unique_key', $request->get('unique_key'))
            ->first();

        if (! $applicant) {
            return back()->withErrors(['msg' => 'Some error occurs, please try reload the form again or contact us.']);
        }
        else if ($applicant->start_test_time) {
            return back()->withErrors(['test_started' => 'Your test task has already been started.']);
        }

        $applicant->update([
            'start_test_time' => now(),
            'status' => 'test started',
        ]);

        return redirect($applicant->jobAppliedFor->test_task_url);
    }
}
