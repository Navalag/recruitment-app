<?php

namespace App\Http\Controllers;

use App\Applicant;
use App\Mail\ApplicantTestTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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
        $applicants = Applicant::latest()->paginate(25);

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
            'phone_number'    => 'numeric',
            'vacancy_id'      => 'numeric|required',
        ]);

        // TODO: check these links in DB
        $startTestLink = '/start-test/' . Str::random(32);
        $finishTestLink = '/finish-test/' . Str::random(32);

        Applicant::create([
            'first_name'       => $request->get('first_name'),
            'last_name'        => $request->get('last_name'),
            'email'            => $request->get('email'),
            'phone_number'     => $request->get('phone_number'),
            'vacancy_id'       => $request->get('vacancy_id'),
            'start_test_link'  => url($startTestLink),
            'finish_test_link' => url($finishTestLink),
            'status'           => 'email sent',
        ]);

        Mail::to($request->get('email'))->send(new ApplicantTestTask($startTestLink, $finishTestLink));

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

        return view('applicant.show', compact('applicant'));
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
            'email'        => 'required|unique:applicants|max:50',
            'phone_number' => 'numeric',
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

        \Session::flash('flash_message', 'Applicant deleted!');

        return redirect('applicant');
    }
}
