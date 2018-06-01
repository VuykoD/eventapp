@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Event Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/datedropper.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/timedropper.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/moment.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/datedropper.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/timedropper.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/jquery.steps.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>
<script async defer 
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQaVZgBql0Qw7hBJJMrlybFenyb5RfcE8">
</script>

<script src="/assets/js/event/manage.js" type="text/javascript"></script>

<script>

    $(".form-steps").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        enableCancelButton: true,
        labels: {
            finish: 'Submit'
        },
        onFinishing: function (event, currentIndex)
        {
            // form.validate().settings.ignore = ":disabled";
            // return form.valid();
            console.log('Validating...');
            return true;
        },
        onFinished: function (event, currentIndex) {
            console.log("Form submitted.");
            $(this).closest('form').submit();
        },
        onCanceled: function (event, currentIndex) {
            console.log("Form canceled.");
            window.location.href = "{{ route('frontend.event.index') }}";
        },
        onInit: function (event, currentIndex) {
            // $('[href=#cancel]').addClass('btn btn-danger');
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            var $form = $('#js-form-create-main');
            return $form.valid(); 
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            if (currentIndex == 4) {
                window.formPreview($('#js-block-review-event'));
            }
        },
    });

    $('.use-select2').select2();
    $('#js-form-create-main').on('change', '.select2', function() {
        $(this).valid();
    });
    $('.use-select2-tags').select2({
        tags: true,
        closeOnSelect: false,
    });
    $('.use-datedropper').dateDropper({ 
        format:'{{ $fmt::FORMAT_DATE_DROPPER }}',
        lock: 'from', 
    });
    $('.use-timedropper').timeDropper({ 
        format:'{{ $fmt::FORMAT_TIME_DROPPER }}', 
        setCurrentTime: false,
        meridians: true,
    });
    $('#js-form-create-main').validate({
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
$( document ).ready(function() {

 $("#steps-uid-0-t-4").on("click", function () {
    setTimeout(HostConfirmation, 500);  
});
});

function HostConfirmation(){
     $( "#checkBox:checked" )
     if ($("#checkBox").prop('checked')==true){
        $("th:contains('Host Confirmation')" ).next().text("yes");
    }else{
        $("th:contains('Host Confirmation')" ).next().text("no");
    }
}

</script>



@endsection


@section('content')
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Event</h4>
                    <div class="heading-elements">
                        @include('partials.event.event-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                    {{ Form::open(['route' => 'frontend.event.store', 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-create-main']) }}

                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="form-body form-steps wizard-notification">

                            {{-- SECTION 1 --}}

                        <h6>Host/Venue Selection</h6>
                        <fieldset>

                            {{-- Name --}}

                            <div class="form-group">
                                {{ Form::label('name', 'Name', []) }}
                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name your event', 'required' => '']) }}
                            </div>

                            {{-- Event Coordinator --}}
                            
                            @if(access()->admin())
                            <div class="form-group">
                                {{ Form::label('coordinator_id', 'Select Coordinator', []) }}
                                {{ Form::select('coordinator_id', $coordinator_list, null, ['class' => 'form-control use-select2', 'required' => '']) }}
                            </div>
                            @endif
                    
                            {{-- Event Hosts --}}
                            @php ($empty_hosts = empty($event_host_list) ? true : false)
                            @php ($event_host_list = ['' => ''] + (empty($event_host_list) ? ['new' => 'Add New Host'] : $event_host_list))

                            <div class="form-group">
                                {{ Form::label('event_host[id]', 'Select Host  ', []) }}
                                {{ Form::select('event_host[id]', $event_host_list, null, ['class' => 'form-control use-select2', 'id' => 'js-select-hosts', 'required' => '', 'data-empty-list' => ($empty_hosts ? '1' : '0')]) }}
                            </div>

                            <div class="form-group">
                                <input id="checkBox" type="checkbox" name="HostCofirmation" checked value='yes'>
                                <label for="checkBox" name="HostCofirmation">Require Event Host Confirmation</label>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modalCreateHost">Create Host</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Event Venues --}}

                            @php ($venue_list = ['' => ''] + (empty($venue_list) ? ['new' => 'Add New Venue'] : $venue_list))

                            <div class="form-group">
                                {{ Form::label('venue_id', 'Select Venue', []) }}
                                {{ Form::select('venue_id', $venue_list, null, ['class' => 'form-control use-select2', 'id' => 'js-select-venues', 'required' => '']) }}
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modalCreateVenue">Create Venue</button>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                            {{-- SECTION 2 --}}                            

                        <h6>Schedule Event</h6>
                        <fieldset>

                            {{-- Schedule --}}

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('event_date', 'Event Date', []) }}
                                        {{ Form::text('event_date', null, ['class' => 'form-control use-datedropper', 'placeholder' => 'Event date', 'readonly' => '', 'required' => '']) }}

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('start_time', 'Event Starts At', []) }}
                                        {{ Form::text('start_time', null, ['class' => 'form-control use-timedropper', 'placeholder' => 'Start time']) }}

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('end_time', 'Event Ends At', []) }}
                                        {{ Form::text('end_time', null, ['class' => 'form-control use-timedropper', 'placeholder' => 'End time']) }}

                                    </div>
                                </div>
                            </div>

                        </fieldset>

                            {{-- SECTION 3 --}}

                        <h6>Event Type/Topics</h6>
                        <fieldset>

                            {{-- Event Type --}}

                            @php ($event_type_list = ['' => ''] + $event_type_list)

                            <div class="form-group">
                                {{ Form::label('event_type_id', 'Select Event Type', []) }}
                                {{ Form::select('event_type_id', $event_type_list, null, ['class' => 'form-control use-select2', 'required' => '']) }}
                            </div>

                            {{-- Event Topics --}}

                            <div class="form-group">
                                {{ Form::label('event_topic[id][]', 'Select Event Topics', []) }}
                                {{ Form::select('event_topic[id][]', $event_topic_list, null, ['class' => 'form-control use-select2-tags', 'multiple' => '', 'required' => '']) }}
                            </div>

                        </fieldset>

                            {{-- SECTION 4 --}}

                        <h6>Event Criteria</h6>
                        <fieldset class="js-event-criteria">                            

                            {{-- Vendor users --}}

                            @php ($category_list = ['' => ''] + $category_list)

                            <div class="row js-category-block">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        {{ Form::label('category[id][]', 'Select Vendor Category', []) }}
                                        {{ Form::select('category[id][]', $category_list, null, ['class' => 'form-control use-select2', 'required' => '']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('vendor_slots[]', 'Vendor Slots', []) }}
                                        {{ Form::text('vendor_slots[]', null, ['class' => 'form-control', 'placeholder' => 'Number of slots', 'required' => '']) }}

                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        {{ Form::label('', '&nbsp;', ['class' => 'hidden-sm-down']) }}
                                        <button type="button" class="btn btn-block btn-outline-danger js-delete-catg-block" disabled>
                                            <i class="icon-remove hidden-sm-down"></i>
                                            <span class="hidden-md-up">Delete Category</span>
                                        </button>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" id="js-button-new-catg" class="btn btn-block btn-outline-primary">Add New Category</button>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                        <h6>Review Event</h6>
                        <fieldset>
                            <div id="js-block-review-event">
                            </div>
                        </fieldset>

                    {{-- End form --}}

                        </div>
                    </div>
                </div>


          {{--       <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="form-actions">

                            <div class="pull-left">
                                {{ link_to_route('frontend.event.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
                            </div>

                            <div class="pull-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs']) }}
                            </div>

                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div> --}}

                    {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.event.modal-event-host')
    @include('partials.event.modal-venue')
    @include('partials.event.form-organization')


@endsection

