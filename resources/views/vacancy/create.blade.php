@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Create New Vacancy</h1>
        <hr/>

        {!! Form::open(['url' => '/vacancy', 'class' => 'form-horizontal', 'files' => true]) !!}

        <div class="form-group {{ $errors->has('job_title') ? 'has-error' : ''}}">
            {!! Form::label('job_title', 'Job Title', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('job_title', null, ['class' => 'form-control']) !!}
                {!! $errors->first('job_title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('test_task_url') ? 'has-error' : ''}}">
            {!! Form::label('test_task_url', 'Test Task URL', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('test_task_url', null, ['class' => 'form-control']) !!}
                {!! $errors->first('test_task_url', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('time_for_task') ? 'has-error' : ''}}">
            {!! Form::label('time_for_task', 'Time For Task (in hours)', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::number('time_for_task', null, ['class' => 'form-control']) !!}
                {!! $errors->first('time_for_task', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('email_subject') ? 'has-error' : ''}}">
            {!! Form::label('email_subject', 'Email Title', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('email_subject', null, ['class' => 'form-control']) !!}
                {!! $errors->first('email_subject', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('email_body') ? 'has-error' : ''}}">
            {!! Form::label('email_body', 'Email Body', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('email_body', null, ['class' => 'form-control']) !!}
                {!! $errors->first('email_body', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('active_status') ? 'has-error' : ''}}">
            {!! Form::label('active_status', 'Active', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::checkbox('active_status', true, true, ['class' => 'form-control']) !!}
                {!! $errors->first('active_status', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-3">
                {!! Form::submit('Create', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>
        {!! Form::close() !!}

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

    </div>
@endsection
