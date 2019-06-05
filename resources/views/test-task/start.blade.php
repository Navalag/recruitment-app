@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1>{{ $applicant->jobAppliedFor->job_title }}</h1>
                <p>Test task explanation</p>
                <strong>Time in minutes</strong>
                <br><br>

                <a href="{{ url('/start-test') }} }}"
                   onclick="event.preventDefault();document.getElementById('start-test-form').submit();"
                   target="_blank">
                    Begin Test
                </a>

                <form id="start-test-form" action="{{ url('/start-test') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $applicant->email }}">
                </form>
            </div>
        </div>

    </div>
@endsection
