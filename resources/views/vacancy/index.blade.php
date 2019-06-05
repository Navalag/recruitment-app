@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Vacancies <a href="{{ url('/vacancy/create') }}" class="btn btn-primary btn-xs" title="Add New Vacancy"><i class="fas fa-plus"></i></a></h1>
        <div class="table">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>S.No</th><th> Job Title </th><th> Test Task URL </th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vacancies as $vacancy)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vacancy->job_title }}</td><td>{{ $vacancy->test_task_url }}</td>
                        <td>
                            <a href="{{ url('/vacancy/' . $vacancy->id) }}" class="btn btn-success btn-xs" title="View Vacancy"><i class="far fa-eye"></i></a>
                            <a href="{{ url('/vacancy/' . $vacancy->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Vacancy"><i class="fas fa-pencil-alt"></i></a>
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/vacancy', $vacancy->id],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<i class="far fa-trash-alt"></i>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
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
