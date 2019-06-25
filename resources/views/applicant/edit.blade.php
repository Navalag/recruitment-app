@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>{{ $applicant->first_name . ' ' . $applicant->last_name }}</h3></div>

                <div class="card-body">
                    {!! Form::model($applicant, [
                        'method' => 'PATCH',
                        'url' => ['/applicant', $applicant->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'enctype' => 'multipart/form-data',
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
                        {!! Form::label('cv_url', 'Upload CV', ['class' => 'col-md-3 col-form-label']) !!}
                        <div class="col-md-9">
                            {!! Form::file('cv_url', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="offset-md-3 col-md-9">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
