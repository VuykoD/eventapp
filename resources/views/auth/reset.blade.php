@extends('auth')

@section('sub_title')
    Reset Password
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>

<script>
    $('#js-form-reset-password').validate({
        // debug: true,
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass(errorClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.closest('.error').find('.select2'));
            } else if (element.parent('.input-group').length) { 
                error.insertAfter(element.parent());
            } else {                                      
                error.insertAfter(element);
            }
        },
        ignore: ":hidden, .no-valid",
    });

    $('#js-form-reset-password').on('submit', function(e) {
        if (!$(this).valid()) {
            e.preventDefault();
            return;
        }
    });
</script>
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

    {{ Form::open(['route' => 'frontend.auth.password.reset', 'class' => 'form', 'id' => 'js-form-reset-password']) }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="row">
            <div class="col-12">
                <div class="form-body">

                            <div class="form-group">
                                {{ Form::label('email', 'Email Address', []) }}
                                <p class="form-control">{{ $email }}</p>
                                {{ Form::hidden('email', $email, []) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('password', trans('validation.attributes.frontend.password'), []) }}
                                {{ Form::input('password', 'password', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.password'), 'required' => '']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('password_confirmation', trans('validation.attributes.frontend.password_confirmation'), []) }}
                                {{ Form::input('password', 'password_confirmation', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.password_confirmation'), 'required' => '', 'data-rule-equalTo' => '#password']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::submit('Create New Password', ['class' => 'btn btn-primary']) }}
                            </div>

                </div>
            </div>
        </div>

    {{ Form::close() }}

@endsection
