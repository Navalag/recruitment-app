@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Vacancies <a href="{{ url('/vacancy/create') }}" class="btn btn-primary btn-sm" title="Add New Vacancy"><i class="fas fa-plus"></i></a></h1>
        <div class="table">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th> Job Title </th><th> Applicants </th><th> Time For Task </th><th> Status </th><th style="min-width: 126px">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vacancies as $vacancy)
                    <tr>
                        <td>{{ $vacancy->job_title }}</td>
                        <td> - </td>
                        <td>{{ $vacancy->time_for_task }}</td>
                        <td>
                            @if ($vacancy->active_status === 1)
                                <span class="badge badge-success">active</span>
                            @else
                                <span class="badge badge-secondary">inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('/vacancy/' . $vacancy->id) }}" class="btn btn-success btn-sm" title="View Vacancy"><i class="far fa-eye"></i></a>
                            <a href="{{ url('/vacancy/' . $vacancy->id . '/edit') }}" class="btn btn-primary btn-sm" title="Edit Vacancy"><i class="fas fa-pencil-alt"></i></a>
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/vacancy', $vacancy->id],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<i class="far fa-trash-alt"></i>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Vacancy',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            )) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper"> {!! $vacancies->render() !!} </div>
        </div>

    </div>
@endsection
