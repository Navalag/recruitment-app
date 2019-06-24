@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ $applicant->jobAppliedFor->job_title }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ url('/start-test') }}">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-warning" role="alert">
                                    {{$errors->first()}}
                                </div>
                            @endif

                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt commodi, cupiditate vel cum eveniet sed qui neque minima iusto, ullam, iure non nobis recusandae totam ipsum corporis voluptate! Nisi, aspernatur.</p>

                            <input type="hidden" name="email" value="{{ $applicant->email }}">
                            <input type="hidden" name="unique_key" value="{{ $applicant->unique_key }}">


                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">

                                    @if($errors->get('test_started'))
                                        <a href="{{ $applicant->jobAppliedFor->test_task_url }}" role="button" class="btn btn-link">
                                            Open Test Task
                                        </a>
                                    @else
                                        <button type="submit" class="btn btn-primary">
                                            Begin Test Task
                                        </button>
                                    @endif

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
