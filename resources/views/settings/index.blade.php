@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Gmail Settings</div>

                    <div class="card-body">
                        @if (! $settings || ! $settings->sign_in_with_google)
                            {!! Form::open([
                                'method'=>'GET',
                                'url' => ['/oauth/gmail'],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('Sign In with google', array(
                                'type' => 'submit',
                                'class' => 'btn btn-primary btn-lg',
                                'title' => 'Sign In',
                            )) !!}
                            {!! Form::close() !!}
                        @else
                            {!! Form::open([
                                'method'=>'GET',
                                'url' => ['/oauth/gmail/logout'],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('Sign Out Gmail', array(
                                'type' => 'submit',
                                'class' => 'btn btn-primary btn-lg',
                                'title' => 'Sign Out',
                            )) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
