<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Vacancy;
use App\Filters\ApplicantFilters;
use App\Settings;
use Illuminate\Http\Request;
use App\Services\GmailService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Exception;

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
     * Show the applicants dashboard.
     *
     * @param ApplicantFilters $filters
     * @return mixed
     */
    public function index(ApplicantFilters $filters)
    {
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
     * @param Request $request
     * @return mixed
     *
     * @throws Exception
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
        $cvFileName = storeCVFile($request);

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

        Session::flash('flash_message', 'Applicant added!');

        return redirect('applicant');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $applicant = Applicant::findOrFail($id);
        $email = $applicant->email;
        $gmailService = app(GmailService::class);
        $mailHistory = '';

        try {
            $mailHistory = Cache::remember('email_history.' . $email, now()->addMinutes(2), function() use ($email, $gmailService) {
                return collect($gmailService->showMessages($email));
            });

            $gmailService->markAsRead($email);
        } catch (\Exception $e) {
            Session::flash('flash_message', 'Please <a href="/gmail-settings">Sign In</a> with Gmail oauth again.');
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
     * @param Request $request
     * @return mixed
     *
     * @throws Exception
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

        updateCVFile($requestData, $applicant);

        $applicant->update($requestData);

        Session::flash('flash_message', 'Applicant updated!');

        return redirect('applicant');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return mixed
     */
    public function destroy($id)
    {
        Applicant::destroy($id);

        if (request()->wantsJson()) {
            return response([], 204);
        }

        Session::flash('flash_message', 'Applicant deleted!');

        return redirect('applicant');
    }

    /**
     * Send email with Gmail api.
     *
     * @param  int $id
     * @return mixed
     */
    public function sendEmail($id)
    {
        $applicant = Applicant::where('id', $id)->with(['jobAppliedFor' => function($query){
            $query->select(['id', 'email_subject', 'email_body', 'time_for_task']);
        }])->first();

        $gmailOauth = Settings::where('user_id', auth()->id())->pluck('sign_in_with_google')->first();

        if (! $gmailOauth) {
            Session::flash('flash_message', 'Sign in with Gmail Oauth first.');

            return redirect('applicant');
        }

        if (! $applicant) {
            Session::flash('flash_message', 'Invalid Applicant ID.');

            return redirect('applicant');
        }

        if ($applicant->status !== 'created') {
            Session::flash('flash_message', 'Email has already been sent.');

            return redirect('applicant');
        }

        try {
            app(GmailService::class)->sendEmail($applicant->email, $applicant->jobAppliedFor->email_subject, $applicant->jobAppliedFor->email_body, $applicant->jobAppliedFor->time_for_task, $applicant->unique_key);
        } catch (\Exception $e) {
            Session::flash('flash_message', 'Sign in with Gmail Oauth first.');

            return redirect('applicant');
        }

        $applicant->update(['status' => 'email sent']);

        if (request()->wantsJson()) {
            return response([], 200);
        }

        Session::flash('flash_message', 'Email Sent!');

        return redirect('applicant');
    }

    /**
     * Get active applicants in default order with pagination
     *
     * @param ApplicantFilters $filters
     * @return Collection
     */
    protected function getApplicants(ApplicantFilters $filters)
    {
        $applicants = Applicant::whereHas('jobAppliedFor', function ($query) {
            $query->active();
        })->orderBy('unread_emails_count', 'desc')->latest()->filter($filters)->paginate(10);

        // append filter query to pagination links
        $applicants->appends($filters->getFilters());

        return $applicants;
    }
}
