@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Applicant <a href="{{ url('/applicant/create') }}" class="btn btn-primary btn-xs" title="Add New Applicant"><span class="glyphicon glyphicon-plus" aria-hidden="true"/></a></h1>
        <div class="table">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>S.No</th><th> First Name </th><th> Last Name </th><th> Email </th><th> Phone Number </th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($applicants as $applicant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $applicant->first_name }}</td><td>{{ $applicant->last_name }}</td><td>{{ $applicant->email }}</td><td>{{ $applicant->phone_number }}</td>
                        <td>
                            <a href="{{ url('/applicant/' . $applicant->id) }}" class="btn btn-success btn-xs" title="View Applicant"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                            <a href="{{ url('/applicant/' . $applicant->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Applicant"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/applicant', $applicant->id],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Customer" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Applicant',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            )) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $applicants->render() !!} </div>
        </div>

    </div>
@endsection
