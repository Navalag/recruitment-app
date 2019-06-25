@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('/finish-test') }}" method="POST">
                            @csrf

                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                     {{ session()->get('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-warning" role="alert">
                                    {{$errors->first()}}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="link">GitHub link</label>
                                <input type="text" name="link" class="form-control" id="link" placeholder="Enter your link here" required>
                            </div>

                            <input type="hidden" name="email" value="{{ $applicant->email }}">
                            <input type="hidden" name="unique_key" value="{{ $applicant->unique_key }}">

                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
