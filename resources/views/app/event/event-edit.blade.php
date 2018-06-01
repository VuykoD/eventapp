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
{{-- <script src="/assets/js/admin/personas.js" type="text/javascript"></script> --}}
{{-- <script src="/assets/js/components/modal/components-modal.js" type="text/javascript"></script> --}}
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/datedropper.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/timedropper.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>
<script async defer 
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQaVZgBql0Qw7hBJJMrlybFenyb5RfcE8&callback=window.initMap">
</script>

<script src="/assets/js/event/manage.js" type="text/javascript"></script>

<script>
    $('.use-select2').select2();
    $('.use-select2-tags').select2({
        tags: true,
        closeOnSelect: false,
    });
    $('.use-datedropper').dateDropper({ format:'{{ $event::FORMAT_DATE_DROPPER }}' });
    $('.use-timedropper').timeDropper({ format:'{{ $event::FORMAT_TIME_DROPPER }}', setCurrentTime: false });

    $('#js-form-edit-event').validate({
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
</script>
@endsection


@section('content')
 {{--    <pre>{{ var_dump($persona_fields) }}</pre>
    <pre>{{ var_dump($persona) }}</pre>
    <pre>{{ var_dump($persona_list) }}</pre> --}}
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Event</h4>
                    <div class="heading-elements">
                        @include('partials.event.event-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                    {{ Form::model($event, ['route' => ['frontend.event.update', $event], 'class' => 'form', 'role' => 'form', 'method' => 'PATCH', 'id' => 'js-form-edit-event']) }}

                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="form-body">


                            <div class="form-group">
                                {{ Form::label('name', 'Name', []) }}
                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name your event', 'required' => '']) }}

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('slug', 'Slug', []) }}
                                        {{ Form::text('slug', null, ['class' => 'form-control', 'placeholder' => 'Event slug', 'required' => '']) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Event Coordinator --}}

                            @if(access()->admin())
                            <div class="form-group">
                                {{ Form::label('coordinator_id', 'Event Coordinator', []) }}
                                {{ Form::select('coordinator_id', $coordinator_list, null, ['class' => 'form-control use-select2', 'required' => '']) }}
                            </div>
                            @endif

                            {{-- Event Hosts --}}

                            @php ($host_id = $event->host() ? $event->host()->id : '')

                            <div class="form-group">
                                {{ Form::label('event_host[id]', 'Select Host', []) }}
                                {{ Form::select('event_host[id]', $event_host_list, $host_id, ['class' => 'form-control use-select2', 'id' => 'js-select-hosts', 'required' => '']) }}
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modalCreateHost">Create Host</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Event Venues --}}

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

                            {{-- Event Type --}}

                            <div class="form-group">
                                {{ Form::label('event_type_id', 'Select Event Type', []) }}
                                {{ Form::select('event_type_id', $event_type_list, null, ['class' => 'form-control use-select2', 'required' => '']) }}
                            </div>

                            {{-- Event Topics --}}

                            @php($topics_id = $event->topics ? $event->topics->pluck('id')->toArray() : '')
                            @php($event_topic_list = $event_topic_list + $event->topics_custom)

                            <div class="form-group">
                                {{ Form::label('event_topic[id][]', 'Select Event Topics', []) }}
                                {{ Form::select('event_topic[id][]', $event_topic_list, $topics_id, ['class' => 'form-control use-select2-tags', 'multiple' => '', 'required' => '']) }}
                            </div>

                            {{-- Vendor users --}}

                            <fieldset class="js-event-criteria">

                                @php($catg_values = $event->categories_slots)
                                @if (empty($catg_values))
                                    @php($catg_values = ['' => ''])
                                @endif                            

                            @foreach ($catg_values as $catg => $slots)
                                
                                <div class="row js-category-block">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::label('category[id][]', 'Select Vendor Category', []) }}
                                            {{ Form::select('category[id][]', $category_list, $catg, ['class' => 'form-control use-select2', 'required' => '']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('vendor_slots[]', 'Vendor Slots', []) }}
                                            {{ Form::text('vendor_slots[]', $slots, ['class' => 'form-control', 'placeholder' => 'Number of slots', 'required' => '']) }}

                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            {{ Form::label('', '&nbsp;', ['class' => 'hidden-sm-down']) }}
                                            <button type="button" class="btn btn-block btn-outline-danger js-delete-catg-block" {{ $loop->first ? 'disabled' : '' }}>
                                                <i class="icon-remove hidden-sm-down"></i>
                                                <span class="hidden-md-up">Delete Category</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>

                            @endforeach

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" id="js-button-new-catg" class="btn btn-block btn-outline-primary">Add New Category</button>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                    
                        </div>
                    </div>
                </div>

                <div class="row">
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
                </div>

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

