<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Vacancy;
use App\Filters\ApplicantFilters;
use App\Mail\ApplicantTestTask;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\GmailService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param ApplicantFilters $filters
     * @return mixed
     */
    public function index(ApplicantFilters $filters)
    {
        // TODO: db queries need to be optimized
        $applicants = $this->getApplicants($filters);
        $vacancies = Vacancy::all();
        $gmailOath = Settings::where('user_id', auth()->id())->pluck('sign_in_with_google')->first();

        if (request()->wantsJson()) {
            return $applicants;
        }

        return view('applicant.index')->with([
            'applicants' => $applicants,
            'vacancies'  => $vacancies,
            'gmailOauth' => $gmailOath,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('applicant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name'      => 'string|required|max:50',
            'last_name'       => 'string|required|max:50',
            'email'           => 'required|unique:applicants|max:50',
            'phone_number'    => 'numeric|nullable',
            'vacancy_id'      => 'numeric|required',
            'cv_url'          => 'nullable|file|max:10000',
        ]);

        $uniqueKey = uniqid();
        $cvFileName = "CV_" . $request->first_name . '_' . $request->last_name . '_' . time() . '.' . request()->cv_url->getClientOriginalExtension();

        $request->cv_url->storeAs('cv_applicants', $cvFileName);

        Applicant::create([
            'first_name'       => $request->get('first_name'),
            'last_name'        => $request->get('last_name'),
            'email'            => $request->get('email'),
            'phone_number'     => $request->get('phone_number'),
            'vacancy_id'       => $request->get('vacancy_id'),
            'unique_key'       => $uniqueKey,
            'status'           => 'created',
            'cv_url'           => $cvFileName,
        ]);

        \Session::flash('flash_message', 'Applicant added!');

        return redirect('applicant');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $applicant = Applicant::findOrFail($id);
        $email = $applicant->email;
        $gmailService = new GmailService();
        $mailHistory = '';

//        Cache::forget('email_history'); // use for dev only
        try {
            $mailHistory = Cache::remember('email_history', now()->addMinutes(2), function() use ($email, $gmailService) {
                return collect($gmailService->showMessages($email));
            });

            $gmailService->markAsRead($email);
        } catch (\Exception $e) {
            // TODO: decide how to handel this exception
        }

        return view('applicant.show')->with([
            'applicant'   => $applicant,
            'mailHistory' => $mailHistory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $applicant = Applicant::findOrFail($id);

        return view('applicant.edit', compact('applicant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'first_name'   => 'string|required|max:50',
            'last_name'    => 'string|required|max:50',
            'email'        => 'required|max:50|unique:applicants,email,'.$id,
            'phone_number' => 'numeric|nullable',
            'cv_url'       => 'nullable|file|max:10000',
        ]);

        $applicant = Applicant::findOrFail($id);
        $requestData = $request->all();

        if ($request->cv_url) {
            Storage::delete('cv_applicants/' . $applicant->cv_url);

            $cvFileName = "CV_" . $applicant->first_name . '_' . $applicant->last_name . '_' . time() . '.' . request()->cv_url->getClientOriginalExtension();
            $request->cv_url->storeAs('cv_applicants', $cvFileName);
            // rewrite appropriate name
            $requestData['cv_url'] = $cvFileName;
        }

        $applicant->update($requestData);

        \Session::flash('flash_message', 'Applicant updated!');

        return redirect('applicant');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Applicant::destroy($id);

        if (request()->wantsJson()) {
            return response([], 204);
        }

        \Session::flash('flash_message', 'Applicant deleted!');

        return redirect('applicant');
    }

    public function sendEmail($id)
    {
        $applicant = Applicant::where('id', $id)->with(['jobAppliedFor' => function($query){
            $query->select(['id', 'email_subject', 'email_body', 'time_for_task']);
        }])->first();

        // TODO: need to be optimized
        $gmailOauth = Settings::where('user_id', auth()->id())->pluck('sign_in_with_google')->first();

        if (! $gmailOauth) {
            \Session::flash('flash_message', 'Sign in with Gmail Oauth first.');

            return redirect('applicant');
        }

        if (! $applicant) {
            \Session::flash('flash_message', 'Invalid Applicant ID.');

            return redirect('applicant');
        }

        if ($applicant->status !== 'created') {
            \Session::flash('flash_message', 'Email has already been sent.');

            return redirect('applicant');
        }

        ( new GmailService )->sendEmail($applicant->email, $applicant->jobAppliedFor->email_subject, $applicant->jobAppliedFor->email_body, $applicant->jobAppliedFor->time_for_task, $applicant->unique_key);
        // below line will send email with standard Laravel api
//        Mail::to($applicant->email)->send(new ApplicantTestTask($applicant->unique_key));

        $applicant->status = 'email sent';
        $applicant->save();

        if (request()->wantsJson()) {
            return response([], 200);
        }

        \Session::flash('flash_message', 'Email Sent!');

        return redirect('applicant');
    }

    /**
     * @param ApplicantFilters $filters
     * @return mixed
     */
    protected function getApplicants(ApplicantFilters $filters)
    {
        $applicants = Applicant::whereHas('jobAppliedFor', function ($query) {
            $query->where('active_status', 1);
        })->latest()->orderBy('unread_emails_count', 'desc')->filter($filters)->paginate(10);

        // append filter query to pagination links
        $applicants->appends($filters->getFilters());

        return $applicants;
    }
}
