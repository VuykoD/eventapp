@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Event Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>

<script src="/assets/js/event/vendors.js" type="text/javascript"></script>

<script>
    $('.use-select2').select2();

    $('#js-form-create-vendor').validate({
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

    $('#js-form-assign-vendor').validate({
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
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                
                @include('partials.spinner', ['spinner_location' =>  'assign-vendor'])
                
                <div class="card-header">
                    <h4 class="card-title">Vendor Management</h4>
                    <div class="heading-elements">
                        @include('partials.event.event-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                    {{ Form::model($event, ['route' => ['frontend.event.vendors.update', $event], 'class' => 'form', 'role' => 'form', 'method' => 'POST', 'id' => 'js-form-assign-vendor']) }}

                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="form-body">


                            <div class="form-group">
                                <h2 class="form-section">{{ $event->name }}</h2>
                            </div>

                            <div class="form-group">
                                {{ Form::label('select_category', 'Select Category', []) }}
                                {{ Form::select('select_category', $event->categories_list, null, ['class' => 'form-control use-select2', 'id' => 'js-select-category', 'required' => '']) }}
                            </div>

                            {{ Form::hidden('action', config('event.action.vendor.invite'), ['id' => 'js-input-action']) }}

                    <div id="js-group-placeholder">

                        @foreach($event->categories_alldata as $catg_id => $catg_data)
                        
                        <fieldset class="js-group-vendor-category" data-catg-id="{{ $catg_id }}">

                            {{ Form::hidden('category', $catg_id, []) }}

                            <h5>Free Slots: {{ $catg_data['slots_free'] . '/' . $catg_data['slots'] }}</h5>

                            <div class="progress">
                                <div class="progress-bar bg-info" 
                                    data-total="{{ $catg_data['slots'] }}" data-free="{{ $catg_data['slots_free'] }}">
                                </div>
                            </div>

                            @if($catg_data['slots_free'] > 0)

                                <div class="form-group js-group-vendor-add">
                                    {{ Form::label('vendor[id][]', 'Select Vendor', []) }}
                                    {{ Form::select('vendor[id][]', $catg_data['vendors_avail'], null, ['class' => 'form-control use-select2', 'required' => '']) }}
                                </div>
                                
                                <div class="row js-group-vendor-add">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-outline-primary js-button-invite" data-action="{{ config('event.action.vendor.invite') }}">Invite Vendor</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-outline-danger js-button-assign" data-action="{{ config('event.action.vendor.assign') }}">Assign Vendor</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#modalCreateVendor">Create Vendor</button>
                                        </div>
                                    </div>
                                </div>

                            @endif

                            @if($catg_data['slots_free'] == 0)

                                <p>Sorry, no free slots</p>

                            @endif

                        </fieldset>

                        @endforeach

                    </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="form-actions">

                            <div class="pull-left">
                                {{ link_to_route('frontend.event.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
                            </div>

                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>

                    {{ Form::close() }}

                    </div>
                </div>
                
                {{-- ALERT --}}
                <div class="alert alert-danger hidden js-alert-display-main">
                </div>

            </div>
        </div>
    </section>

    @include('partials.event.modal-event-vendor')

@endsection

