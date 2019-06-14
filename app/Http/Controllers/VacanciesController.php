<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vacancy;

class VacanciesController extends Controller
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
        $vacancies = Vacancy::latest()->paginate(10);

        return view('vacancy.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vacancy.create');
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
        // TODO: try to fix it in a better way
        if( !$request->has('active_status') ) {
            $request->merge(['active_status' => 0]);
        }
        $this->validate($request, [
            'job_title'     => 'string|required|max:50',
            'test_task_url' => 'string|max:255|nullable',
            'time_for_task' => 'numeric|max:100|min:1|nullable',
            'email_subject' => 'string|max:255|nullable',
            'email_body'    => 'string|nullable',
            'active_status' => 'boolean|required',
        ]);

        Vacancy::create($request->all());

        \Session::flash('flash_message', 'Vacancy added!');

        return redirect('vacancy');
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
        $vacancy = Vacancy::findOrFail($id);

        return view('vacancy.show', compact('vacancy'));
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
        $vacancy = Vacancy::findOrFail($id);

        return view('vacancy.edit', compact('vacancy'));
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
        // TODO: try to fix it in a better way
        if( !$request->has('active_status') ) {
            $request->merge(['active_status' => 0]);
        }
        $this->validate($request, [
            'job_title'     => 'string|required|max:50',
            'test_task_url' => 'string|required|max:255|nullable',
            'time_for_task' => 'numeric|max:100|min:1|nullable',
            'email_subject' => 'string|max:255|nullable',
            'email_body'    => 'string|nullable',
            'active_status' => 'boolean|required',
        ]);

        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update($request->all());

        \Session::flash('flash_message', 'Vacancy updated!');

        return redirect('vacancy');
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
        Vacancy::destroy($id);

        \Session::flash('flash_message', 'Vacancy deleted!');

        return redirect('vacancy');
    }
}
