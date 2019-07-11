@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Create New Vacancy</h3></div>

                <div class="card-body">
                    {!! Form::open([
                        'url' => '/vacancy',
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group row">
                        {!! Form::label('job_title', 'Job Title', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::text('job_title', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('test_task_url', 'Test Task URL', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::text('test_task_url', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('time_for_task', 'Time For Task (in hours)', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::number('time_for_task', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('email_subject', 'Email Title', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::text('email_subject', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('email_body', 'Email Body', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::textarea('email_body', null, ['class' => 'form-control', 'rows' => 4]) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('active_status', 'Active', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::checkbox('active_status', true, true, ['class' => 'form-control']) !!}
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
