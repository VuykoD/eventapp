@extends('app')

@section('page-header')
    
@endsection

@section('css')
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>

<script src="/assets/js/event/tpl-edit.js" type="text/javascript"></script>

<script>
    $('#js-form-edit-templates').validate({
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

    $('#js-form-edit-templates').on('submit', function(e) {
        if (!$(this).valid()) {
            e.preventDefault();
            return;
        }
    })
</script>
@endsection


@section('content')

<?php

    $tpl_list = [
        'Header' => 'event.email.template.header',
        'From' => 'event.email.template.from',
        'Vendor: Confirm invitation' => 'event.email.template.vendor.confirm',
        'Vendor: Assigned' => 'event.email.template.vendor.assigned',
        'Vendor: Submission Approved' => 'event.email.template.vendor.approved',
        'Vendor: Submission Denied' => 'event.email.template.vendor.denied',
        'Host: Confirm hosting of an event' => 'event.email.template.host.invited',
        'Host confirmed hosting' => 'event.email.template.coordinator.host_commit',
        'Host declined hosting' => 'event.email.template.coordinator.host_declined',
        'Vendor requested invitation' => 'event.email.template.coordinator.vendor_invite',
        'Event postponed' => 'event.email.template.event.postponed',
        'Event cancelled' => 'event.email.template.event.cancelled',
        'Footer' => 'event.email.template.footer',
    ];

?>
  
<section class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit E-Mail Templates</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">



                        {{ Form::open(['route' => 'frontend.update.tpl', 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-edit-templates']) }}

                        <div class="form-body">
                        
                            <div class="form-group">
                                <p>You should always include <%LINK|Button text%> in a template otherwise it falls back to some default text.</p>
                            </div>

                            @foreach($tpl_list as $tpl_name => $tpl_key)
                                @php($form_key = 'tpl[' . $tpl_key . ']')
                                <div class="form-group">
                                    {{ Form::label($form_key, $tpl_name, []) }}
                                    {{ Form::textarea($form_key, App\Models\Event\TemplateRepo::get($tpl_key), ['class' => 'form-control', 'required' => '']) }}
                                </div>

                            @endforeach

                       </div>

                       <div class="form-actions">

                            <div class="pull-left">
                                {{ link_to_route('frontend.event.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
                            </div>

                            <div class="pull-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs']) }}
                            </div>

                            <div class="clearfix"></div>

                        </div>

                       {{ Form::close() }}

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection