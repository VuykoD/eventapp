@extends('auth')

@section('sub_title')
    Reset Password
@endsection

@section('content')
    <div class="row" style="margin-top: -22px; margin-bottom: 22px;">
            <div class="col-12">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

        </div>
    </div>

    {{ Form::open(['route' => 'frontend.auth.password.email', 'class' => 'form']) }}

        <div class="row">
            <div class="col-12">
                <div class="form-body">

                            <div class="form-group">
                                {{ Form::label('email', 'Email Address', []) }}
                                {{ Form::text('email', null, ['class' => 'form-control', 'required' => '', 'placeholder' => 'Your Email']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::submit('Send Password Reset Link', ['class' => 'btn btn-primary']) }}
                            </div>

                </div>
            </div>
        </div>

    {{ Form::close() }}

@endsection