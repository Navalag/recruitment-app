<?php

namespace App\Http\Controllers\TestTasks;

use App\Applicant;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class FinishTestTaskController extends Controller
{
    /**
     * Show finish task view.
     *
     * @param $key
     * @return \Illuminate\View\View
     */
    public function index($key)
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
    public function finishTask(Request $request)
    {
        $this->validate($request, [
            'email'      => 'required|email|max:50',
            'unique_key' => 'required|string|max:255',
            'link'       => 'required|string|max:255',
        ]);

        $applicant = Applicant::where('email', $request->get('email'))
            ->where('unique_key', $request->get('unique_key'))
            ->first();

        if (! $applicant) {
            return back()->withErrors(['msg' => 'Some error occurs, please try reload the form again or contact us.']);
        }
        else if (! $applicant->start_test_time) {
            return back()->withErrors(['msg' => 'Your test task has not been started yet, please contact us.']);
        }
        else if ($applicant->finish_test_time) {
            return back()->withErrors(['msg' => 'Your test task has already been finished, please contact us.']);
        }

        $applicant->update([
            'finish_test_time' => now(),
            'test_task_link' => $request->get('link'),
            'status' => 'test finished',
        ]);

        return back()->with('success', 'Thank you for your time, we will get in contact soon.');
    }
}
