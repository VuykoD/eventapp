@extends ('app')

@section('page-header')
    <h2 class="content-header-title">{{-- {{ $event->name }} --}}</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/switchery.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/switchery.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>

<script>
    $('.switchery').each(function(){
        new Switchery(this, {size: 'small'});
    });

    $('#js-form-host-invite').validate({
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
                error.insertAfter(element.closest('label'));
            }
        },
        ignore: ":hidden, .no-valid",
    });
</script>

@if(!isset($logged_in_user))
    <script>
        $('body').removeClass('vertical-content-menu');
    </script>
@endif

<script src="/assets/js/event/host-invite.js" type="text/javascript"></script>
@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Please confirm hosting of an event "{{ $event->name }}"</h4>
                    <div class="heading-elements">
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                        <div class="form-body">

                            <h4>Event details</h4>

                            <div class="row">
                                <div class="col12">
                                
                                    @include('partials.event.block-event-view') 

                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group"> 
                                        <label for="cond1" class="mr-1">Accept With Conditions</label>
                                        <input type="checkbox" name="cond1" id="js-host-conditions" class="switchery"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group hidden" id="js-group-conditions">
                                {{ Form::label('cond2', 'Your Message', []) }}
                                {{ Form::textarea('cond2', null, ['class' => 'form-control', 'placeholder' => 'Please state your request', 'id' => 'js-text-conditions']) }}
                            </div>

                            <div class="clearfix" style="margin-top: 22px;"></div>

                        {{ Form::open(['route' => ['frontend.host.decline', $uid], 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-host-decline']) }}
                                
                            <div class="pull-left">
                                {{ Form::submit('Decline', ['class' => 'btn btn-danger btn-xs']) }}
                            </div>

                        {{ Form::close() }}

                            <div class="pull-right">
                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalHostCommitment">Accept</button>
                            </div>

                            <div class="clearfix"></div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.event.modal-host-commit')

@endsection

