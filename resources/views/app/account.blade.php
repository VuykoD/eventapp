@extends('app')

@section('page-header')
    
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/dropzone.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/cropper.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/extensions/dropzone.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/cropper.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>

<script src="/assets/js/event/avatar.js" type="text/javascript"></script>

<script>
$('#js-img-cropper').cropper({
  aspectRatio: 1 / 1,
  minCropBoxWidth:200,
  minCropBoxHeight:200,
  minContainerHeight: 400,
  minContainerWidth: 400,
  dragMode: 'move',
  viewMode: 1,
  crop: function(e) {
    // Output the result data for cropping image.
    window.crop_data = {
        x: e.x,
        y: e.y,
        width: e.width,
        height: e.height,
        rotate: e.rotate,
        scaleX: e.scaleX,
        scaleY: e.scaleY,
    }
  }
});
</script>

{{-- <script>
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
</script> --}}
{{-- <script>
    $('#js-form-edit-notes').validate({
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
</script> --}}
@endsection


@section('content')
  
<section class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">My Profile</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-underline">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" aria-controls="profile" href="#profile-tab" aria-expanded="true"><i class="icon-head"></i> Profile</a>
                            </li>
                            @permissions(['update-profile'])
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" aria-controls="edit" href="#edit-tab" aria-expanded="false"><i class="icon-list-alt"></i> Update</a>
                            </li>
                            @endauth
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" aria-controls="password-tab" href="#password-tab" aria-expanded="false"><i class="icon-lock2"></i> Change Password</a>
                            </li>
                        </ul>

                        <div class="tab-content px-1 pt-1">
                            <div role="tabpanel" class="tab-pane active" id="profile-tab">
                                @include('partials.tabs.profile')
                            </div><!--tab panel profile-->
                            @permissions(['update-profile'])
                            <div class="tab-pane" id="edit-tab">
                                @include('partials.tabs.edit')
                            </div><!--tab panel edit profile-->
                            @endauth
                            <div class="tab-pane" id="password-tab" >
                                @include('partials.tabs.change-password')
                            </div><!--tab panel change password-->
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection