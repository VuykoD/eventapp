@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Complete Event</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/editors/tinymce/tinymce.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/raty/jquery.raty.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/editors/tinymce/tinymce.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/jquery.steps.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/jquery.raty.js" type="text/javascript"></script>

<script src="/assets/js/event/complete.js" type="text/javascript"></script>

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
        onStepChanged: function (event, currentIndex, priorIndex) {
            if (currentIndex == 3) {
                window.formPreview($('#js-block-review-complete'));
            }
        },
    });

    $('.use-select2').select2();

    $.fn.raty.defaults.path="/assets/images/raty/"
    $('.use-raty').raty({scoreName: 'rating'});
    $('.use-raty').each(function(){
        var $this = $(this);
        var name = $this.attr('data-input-name');
        if (name) {
            $this.find('input[name=rating]').attr('name', name);
        }
    });

    tinymce.init({
        selector: '.use-rtf-editor',
        height: 200,
        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | hr removeformat | print fullscreen",

        menubar: false,
        toolbar_items_size: 'small',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        content_css: '/assets/css/plugins/editors/tinymce/content.min.css',
    });
    
</script>
@endsection


@section('content')
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $event->name }}</h4>
                    <div class="heading-elements">
                        {{-- @include('partials.event.event-header-buttons') --}}
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                    {{ Form::open(['route' => ['frontend.event.complete.post', $event->id], 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-complete-main']) }}

                    {{ Form::hidden('completed', 1, []) }}
                    {{ Form::hidden('status', config('event.status.completed'), []) }}

                <div class="row">
                    <div class="col-xl-8 offset-xl-2">
                        <div class="form-body form-steps wizard-notification">

                            {{-- SECTION 1 --}}

                        <h6>Attendance</h6>
                        <fieldset>

                            {{-- Attendees --}}

                            <div class="form-group">
                                {{ Form::label('num_attendees', 'Number of Attendees', []) }}
                                {{ Form::text('num_attendees', null, ['class' => 'form-control', 'placeholder' => 'Attendees', 'required' => '']) }}
                            </div>
                    
                            {{-- Attendance --}}

                            <div class="form-group">
                                {{ Form::label('att_id', 'Attendance', []) }}
                                {{ Form::select('att_id', config('event.attendance'), 0, ['class' => 'form-control', 'required' => '']) }}
                            </div>
                            
                        </fieldset>

                            {{-- SECTION 2 --}}                            

                        <h6>Comment</h6>
                        <fieldset>

                            {{-- Comments --}}

                            <div class="form-group">
                                {{ Form::label('complete_message', 'Event comment', []) }}
                                {{ Form::textarea('complete_message', null, ['class' => 'form-control use-rtf-editor', 'placeholder' => 'Please comment the event']) }}

                            </div>

                        </fieldset>

                            {{-- SECTION 3 --}}

                        <h6>Vendor Feedback</h6>
                        <fieldset>

                        {{-- Event Vendors --}}

                        @php($counter = 1)
                        @foreach($event->vendors_list as $vendor_id => $vendor)

                            <div class="js-vendor-feedback-block" data-vendor-id="{{ $vendor_id }}" data-block-id="{{ $counter }}">

                                {{ Form::hidden('vendor[' . $vendor_id . '][id]', $vendor_id, []) }}

                                <div class="form-group">
                                    <h2 class="form-section">{{ $vendor }}</h2>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            General Rating
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="use-raty" data-input-name="vendor[{{$vendor_id}}][rating]"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('vendor[' . $vendor_id . '][attendance]', 'Attendance', []) }}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {{ Form::select('vendor[' . $vendor_id . '][attendance]', config('event.vendor.attendance'), 0, ['class' => 'form-control', 'required' => '']) }}
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    {{ Form::label('vendor[' . $vendor_id . '][comments]', 'Comments', []) }}
                                    {{ Form::textarea('vendor[' . $vendor_id . '][comments]', null, ['class' => 'form-control use-rtf-editor', 'placeholder' => 'Please comment the vendor']) }}

                                </div>

                            </div>

                        @php($counter++)
                        @endforeach

                        @if($counter > 2)
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <button data-block-id="0" data-max-id="{{ $counter - 1 }}" type="button" id="js-button-prev-vendor" class="btn btn-block btn-outline-secondary">Previous</button>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <button data-block-id="2" data-max-id="{{ $counter - 1 }}" type="button" id="js-button-next-vendor" class="btn btn-block btn-outline-primary">Next Vendor</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        </fieldset>

                            {{-- SECTION 4 --}}

                            {{-- Review before submitting --}}

                        <h6>Review Event</h6>
                        <fieldset>
                            <div id="js-block-review-complete">
                            </div>
                        </fieldset>

                    {{-- End form --}}

                        </div>
                    </div>
                </div>

                    {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

