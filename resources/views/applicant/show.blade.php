@extends('layouts.app')

@section('content')
<div class="container">

    <h1>{{ $applicant->first_name . ' ' . $applicant->last_name }}
        <a href="{{ url('applicant/' . $applicant->id . '/edit') }}" class="btn btn-primary btn-sm" title="Edit Applicant"><i class="fas fa-pencil-alt"></i></a>
        {!! Form::open([
            'method'=>'DELETE',
            'url' => ['applicant', $applicant->id],
            'style' => 'display:inline'
        ]) !!}
            {!! Form::button('<i class="far fa-trash-alt"></i>', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-sm',
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
                @if($applicant->cv_url)
                    <tr>
                        <th> Candidate CV </th><td> <a href="{{ url('/storage/cv_applicants/' . $applicant->cv_url) }}" target="_blank">Open Resume</a> </td>
                    </tr>
                @endif
                @if ($applicant->test_task_link)
                    <tr>
                        <th> Test Task Link </th><td> {{ $applicant->test_task_link }} </td>
                    </tr>
                @endif
                @if ($mailHistory)
                    <tr>
                        <th> - </th><td> - </td>
                    </tr>
                    <tr>
                        <th> Email Subject </th><td> Email Body </td>
                    </tr>
                    @foreach($mailHistory as $mail)
                    <tr>
                        <th> {{ $mail['subject'] }} </th><td> {{ $mail['body'] }} </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

</div>
@endsection
