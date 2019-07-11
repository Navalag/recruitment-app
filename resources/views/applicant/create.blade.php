@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Create New Applicant</h3></div>

                <div class="card-body">
                    {!! Form::open([
                        'url' => '/applicant',
                        'class' => 'form-horizontal',
                        'files' => true,
                        'enctype' => 'multipart/form-data'
                    ]) !!}

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group row">
                        {!! Form::label('first_name', 'First Name', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('last_name', 'Last Name', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('email', 'Email', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::email('email', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('phone_number', 'Phone Number', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('job_title', 'Job Applied For', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            <select name="vacancy_id" class="form-control">
                                @foreach( \App\Vacancy::all() as $vacancy )
                                    <option value="{{ $vacancy->id }}">{{ $vacancy->job_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('cv_url', 'Upload CV', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::file('cv_url', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('cv_url', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="offset-md-3 col-md-9">
                            {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
