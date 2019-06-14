<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Mail\ApplicantTestTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\GmailService;
use Illuminate\Support\Facades\Cache;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $applicants = Applicant::whereHas('jobAppliedFor', function ($query){
            $query->where('active_status', 1);
        })->latest()->paginate(25);

//        Cache::forget('unread_emails_list');
        $unreadEmailList = Cache::remember('unread_emails_list', now()->addMinutes(2), function() {
            return ( new GmailService() )->getAllUnreadEmailsSenders();
        });

        $applicants->transform(function (Applicant $applicant) use ($unreadEmailList) {
            $applicant->unread_emails_count = $this->countUnreadEmails($unreadEmailList, $applicant);

            return $applicant;
        });

        return view('applicant.index', compact('applicants'));
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
        ]);

        $uniqueKey = uniqid();

        Applicant::create([
            'first_name'       => $request->get('first_name'),
            'last_name'        => $request->get('last_name'),
            'email'            => $request->get('email'),
            'phone_number'     => $request->get('phone_number'),
            'vacancy_id'       => $request->get('vacancy_id'),
            'unique_key'       => $uniqueKey,
            'status'           => 'created',
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

//        Cache::forget('email_history');
        $mailHistory = Cache::remember('email_history', now()->addMinutes(2), function() use ($email, $gmailService) {
            return collect($gmailService->showMessages($email));
        });

        $gmailService->markAsRead($email);

        Cache::forget('unread_emails_list');

        return view('applicant.show')->with([
            'applicant' => $applicant,
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'first_name'   => 'string|required|max:50',
            'last_name'    => 'string|required|max:50',
            'email'        => 'required|max:50|unique:applicants,email,'.$id,
            'phone_number' => 'numeric|nullable',
        ]);

        $applicant = Applicant::findOrFail($id);
        $applicant->update($request->all());

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
        $applicant = Applicant::where('id', $id)->first();

        if (! $applicant) {
            \Session::flash('flash_message', 'Invalid Applicant ID.');

            return redirect('applicant');
        }

        if ($applicant->status !== 'created') {
            \Session::flash('flash_message', 'Email has already been sent.');

            return redirect('applicant');
        }

        ( new GmailService )->sendEmail($applicant->email, 'Test', $applicant->unique_key);
//        Mail::to($applicant->email)->send(new ApplicantTestTask($applicant->unique_key));

        $applicant->status = 'email sent';
        $applicant->save();

        if (request()->wantsJson()) {
            return response([], 200);
        }

        \Session::flash('flash_message', 'Email Sent!');

        return redirect('applicant');
    }

    private function countUnreadEmails($unreadEmailList, $applicant)
    {
        $count = 0;

        $unreadEmailList->each(function ($email) use ($applicant, &$count) {
            if ($email === $applicant->email) $count++;
        });

        return $count;
    }
}
