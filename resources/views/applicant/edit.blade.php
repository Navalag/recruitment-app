@extends('layouts.app')

@section('content')
<div class="container">

    <h1 style="margin-bottom: 25px;">{{ $applicant->first_name . ' ' . $applicant->last_name }}</h1>

    {!! Form::model($applicant, [
        'method' => 'PATCH',
        'url' => ['/applicant', $applicant->id],
        'class' => 'form-horizontal',
        'files' => true,
        'enctype' => 'multipart/form-data',
    ]) !!}

    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
        {!! Form::label('first_name', 'First Name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
            {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
        {!! Form::label('last_name', 'Last Name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
            {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
        {!! Form::label('email', 'Email', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::email('email', null, ['class' => 'form-control']) !!}
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
        {!! Form::label('phone_number', 'Phone Number', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
            {!! $errors->first('phone_number', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('cv_url') ? 'has-error' : ''}}">
        {!! Form::label('cv_url', 'Upload CV', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::file('cv_url', null, ['class' => 'form-control']) !!}
            {!! $errors->first('cv_url', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
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
