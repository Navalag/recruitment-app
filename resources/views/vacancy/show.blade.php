@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Vacancy {{ $vacancy->id }}
        <a href="{{ url('vacancy/' . $vacancy->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Vacancy"><i class="fas fa-pencil-alt"></i></a>
        {!! Form::open([
            'method'=>'DELETE',
            'url' => ['vacancy', $vacancy->id],
            'style' => 'display:inline'
        ]) !!}
            {!! Form::button('<i class="far fa-trash-alt"></i>', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-xs',
                    'title' => 'Delete Vacancy',
                    'onclick'=>'return confirm("Confirm delete?")'
            ))!!}
        {!! Form::close() !!}
    </h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th>ID</th><td>{{ $vacancy->id }}</td>
                </tr>
                <tr>
                    <th> Job Title </th><td> {{ $vacancy->job_title }} </td>
                </tr>
                <tr>
                    <th> Test Task URL </th><td> {{ $vacancy->test_task_url }} </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
