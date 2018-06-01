@extends('auth')


@section('content')

   {{ Form::open(['route' => 'frontend.auth.login', 'class' => 'form-horizontal']) }}
       <fieldset class="form-group has-feedback has-icon-left">
           {{ Form::text('email', null, ['class' => 'form-control form-control-lg input-lg', 'placeholder' => 'Your Email', 'required' => '']) }}
           <div class="form-control-position">
               <i class="icon-head"></i>
           </div>
       </fieldset>
       <fieldset class="form-group has-feedback has-icon-left">
           {{ Form::password('password', ['class' => 'form-control form-control-lg input-lg', 'placeholder' => 'Enter Password', 'required' => '']) }}
           <div class="form-control-position">
               <i class="icon-key3"></i>
           </div>
       </fieldset>
       <fieldset class="form-group row">
           <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
               <fieldset>
                   {{ Form::checkbox('remember', 1, null, ['class' => 'chk-remember']) }}
                   <label for="remember-me"> Remember Me</label>
               </fieldset>
           </div>
           <div class="col-md-6 col-xs-12 text-xs-center text-md-right">
           {{ link_to_route('frontend.auth.password.reset', 'Forgot Password?', [], ['class' => 'card-link']) }}
           </div>
       </fieldset>

       <button type="submit" class="btn btnc-login btn-lg btn-block"><i class="icon-unlock2"></i> Login</button>
   {{ Form::close() }}

@endsection

@section('vendor_login')
  <a href="{{ route('frontend.auth.vendor.signup') }}" class="card-link vendor-registration-link">Vendor Registration</a>
@endsection