<?php

namespace App\Http\Controllers;

use App\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        $applicants = Applicant::paginate(25);

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
     */
    public function store(Request $request)
    {
        //TODO: add validation

        $requestData = $request->all();

        Applicant::create($requestData);

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
     */
    public function update($id, Request $request)
    {
        // TODO: add validation
        $requestData = $request->all();

        $applicant = Applicant::findOrFail($id);
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

        \Session::flash('flash_message', 'Applicant deleted!');

        return redirect('applicant');
    }
}
