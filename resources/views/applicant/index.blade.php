@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="level">
            <span class="flex">
                <h1 class="mb-3">Applicants <a href="{{ url('/applicant/create') }}" class="btn btn-primary btn-sm" title="Add New Applicant"><i class="fas fa-plus"></i></a></h1>
            </span>
            <form class="form-search" action="{{ url('/applicant') }}" method="get" role="search">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search...">
                </div>
            </form>
        </div>

        <div class="table">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th class="align-middle"> Name </th>
                    <th>
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" style="color: black;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Position
                            </a>
                            <div class="dropdown-menu scrollable-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ url('/applicant') }}">all</a>
                                @foreach($vacancies as $vacancy)
                                    <a class="dropdown-item" href="{{ url("/applicant?position=$vacancy->id") }}">{{ $vacancy->job_title }}</a>
                                @endforeach
                            </div>
                        </div>
                    </th>
                    <th class="align-middle"> Email </th>
                    <th>
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" style="color: black;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Status
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ url('/applicant') }}">all</a>
                                <a class="dropdown-item" href="{{ url('/applicant?status=created') }}">created</a>
                                <a class="dropdown-item" href="{{ url('/applicant?status=email sent') }}">email sent</a>
                                <a class="dropdown-item" href="{{ url('/applicant?status=test started') }}">test started</a>
                                <a class="dropdown-item" href="{{ url('/applicant?status=test finished') }}">test finished</a>
                            </div>
                        </div>
                    </th>
                    <th class="align-middle">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($applicants as $applicant)
                    <tr>
                        <td>{{ $applicant->first_name . ' ' . $applicant->last_name }}</td>
                        <td>{{ $applicant->jobAppliedFor->job_title }}</td>
                        <td>{{ $applicant->email }}</td>
                        <td>
                            @if ($applicant->status === 'created')
                                <span class="badge badge-secondary"> created</span>
                            @elseif ($applicant->status === 'email sent')
                                <span class="badge badge-primary"> email sent</span>
                            @elseif ($applicant->status === 'test started' || $applicant->status === 'test finished')
                                <span class="badge badge-warning">begin:</span> <small>{{ $applicant->start_test_time->diffForHumans() }}</small>
                            @endif
                            @if ($applicant->status === 'test finished')
                                    <br>
                                <span class="badge badge-success">finish:</span> <small>{{ $applicant->finish_test_time->diffForHumans() }}</small>
                                    <br>
                                <span class="badge badge-light">time spent:</span> <small>{{ $applicant->finish_test_time->diffInHours($applicant->start_test_time) . ' ' . Str::plural('hour', $applicant->finish_test_time->diffInHours($applicant->start_test_time)) }}</small>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('/applicant/' . $applicant->id) }}" class="btn btn-success btn-sm" title="View Applicant"><i class="far fa-eye"></i></a>
                            <a href="{{ url('/applicant/' . $applicant->id . '/edit') }}" class="btn btn-primary btn-sm" title="Edit Applicant"><i class="fas fa-pencil-alt"></i></a>
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/applicant', $applicant->id],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<i class="far fa-trash-alt"></i>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Applicant',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            )) !!}
                            {!! Form::close() !!}
                            {!! Form::open([
                                'method'=>'GET',
                                'url' => ['/applicant/' . $applicant->id . '/send-email'],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<i class="far fa-envelope"></i>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-dark btn-sm',
                                    'title' => 'Send Email',
                                    'onclick'=>'return confirm("Confirm send email?")',
                                    !$gmailOauth ? 'disabled' : (($applicant->status !== 'created') ? 'disabled' : '')
                            )) !!}
                            {!! Form::close() !!}
                            @if ($applicant->unread_emails_count)
                                <span class="badge badge-danger">{{ $applicant->unread_emails_count }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {{ $applicants->links() }} </div>
        </div>

    </div>
@endsection
