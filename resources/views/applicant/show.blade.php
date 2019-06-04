@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Applicant {{ $applicant->id }}
        <a href="{{ url('applicant/' . $applicant->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Applicant"><i class="fas fa-pencil-alt"></i></a>
        {!! Form::open([
            'method'=>'DELETE',
            'url' => ['applicant', $applicant->id],
            'style' => 'display:inline'
        ]) !!}
            {!! Form::button('<i class="far fa-trash-alt"></i>', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-xs',
                    'title' => 'Delete Applicant',
                    'onclick'=>'return confirm("Confirm delete?")'
            ))!!}
        {!! Form::close() !!}
    </h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th>ID</th><td>{{ $applicant->id }}</td>
                </tr>
                <tr>
                    <th> First Name </th><td> {{ $applicant->first_name }} </td>
                </tr>
                <tr>
                    <th> Last Name </th><td> {{ $applicant->last_name }} </td>
                </tr>
                <tr>
                    <th> Email </th><td> {{ $applicant->email }} </td>
                </tr>
                <tr>
                    <th> Phone Number </th><td> {{ $applicant->phone_number }} </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
