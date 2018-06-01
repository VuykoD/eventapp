@extends('app')

@section('page-header')
    <h2 class="content-header-title">Event Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/dropzone.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/editors/tinymce/tinymce.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/editors/tinymce/tinymce.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/dropzone.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>

<script src="/assets/js/event/notes.js" type="text/javascript"></script>

<script>
    $('.use-select2').select2();
</script>
<script>
    Dropzone.autoDiscover = false;

    $("#dp-accept-files").dropzone({ 
        paramName: "docs", 
        acceptedFiles: ".pdf", 
        maxFilesize: 15,
        init: function(){
            this.on("error", function(file, errorMessage, xhr) {
                toastr.error('Upload failed: ' + errorMessage);
            });
            this.on("success", function(file, response) {
                var json;
                try {
                    json = JSON.parse(response);
                } catch(e) {
                    json = response;
                }
                toastr.success((json.message || 'Success') + ': ' + file.name);
            });
        },
    });
</script>
<script>
    window.validator_notes = $('#js-form-edit-notes').validate({
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

    window.validator_docs = $('#js-form-add-documents').validate({
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

    window.validator_email = $('#js-form-email-all').validate({
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
<script>
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
                // $('.use-rtf-editor').valid();
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
                <h4 class="card-title">{{ $event->name ?? '' }}</h4>
                <div class="heading-elements">
                    @include('partials.event.event-header-buttons')
                </div>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">
                        @include('partials.spinner', ['spinner_location' =>  'notes-tabs'])
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-underline">
                            
                            {{-- @permissions(['update-profile']) --}}
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#notes-tab"><i class="icon-clipboard2"></i> Notes</a>
                            </li>
                            @php ($conditions_message = $event->conditions_message())
                            @if ($conditions_message)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#host-conditions-tab"><i class="icon-exclamation"></i> Host Conditions</a>
                            </li>
                            @endif
                            {{-- @endauth --}}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#documents-tab"><i class="icon-file-text2"></i> Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mail-all-tab"><i class="icon-envelop"></i> Email All Users</a>
                            </li>
                        </ul>

                        <div class="tab-content px-1 pt-1">
                            
                {{-- NOTES --}}
                            <div class="tab-pane active" id="notes-tab">
                                {{ Form::open(['route' => ['frontend.event.notes.update', $event], 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-edit-notes']) }}
                                
                                <div class="form-body">

                                    <div class="form-group">
                                        {{ Form::label('notes', 'Notes', ['class' => 'col-lg-2 control-label']) }}

                                        <div class="col-lg-10">
                                            {{ Form::textarea('notes', $event->get_notes(), ['class' => 'form-control use-rtf-editor', 'placeholder' => 'Your notes', 'required' => '']) }}
                                        </div>
                                    </div>

                                    <div class="clearfix hidden-md-down row-spacer"></div>


                                    <div class="pull-left">
                                        {{ link_to_route('frontend.event.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                                    </div><!--pull-left-->

                                    <div class="pull-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs']) }}
                                    </div><!--pull-right-->

                                    <div class="clearfix"></div>

                                </div>

                                {{ Form::close() }}
                            </div>

                {{-- HOST CONDITIONS --}}
                            @if ($conditions_message)
                            <div class="tab-pane" id="host-conditions-tab">
                                <p class="form-control">{{ $conditions_message }}</p>
                            </div>
                            @endif

                {{-- DOCUMENTS --}}
                            <div class="tab-pane" id="documents-tab" >
                                {{ Form::open(['route' => ['frontend.event.docs.upload', $event], 'class' => 'form dropzone', 'role' => 'form', 'method' => 'post', 'id' => 'dp-accept-files']) }}
                                {{ Form::close() }}
                            </div>

                {{-- ALL MAIL --}}
                            <div class="tab-pane" id="mail-all-tab" >

                                {{ Form::open(['route' => ['frontend.event.email_all', $event], 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-email-all']) }}
                                
                                <div class="form-body">

                                    <div class="form-group">
                                        {{ Form::label('email_message', 'Email message', ['class' => 'col-lg-2 control-label']) }}

                                        <div class="col-lg-10">
                                            {{ Form::textarea('email_message', null, ['class' => 'form-control', 'placeholder' => 'Your message', 'required' => '']) }}
                                        </div>
                                    </div>

                                    <div class="clearfix hidden-md-down row-spacer"></div>


                                    <div class="pull-left">
                                        {{ link_to_route('frontend.event.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                                    </div><!--pull-left-->

                                    <div class="pull-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs']) }}
                                    </div><!--pull-right-->

                                    <div class="clearfix"></div>

                                </div>

                                {{ Form::close() }}

                            </div>
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection