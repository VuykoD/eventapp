@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Organization Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/cropper.min.js" type="text/javascript"></script>
<script async defer 
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQaVZgBql0Qw7hBJJMrlybFenyb5RfcE8">
</script>

<script src="/assets/js/admin/orgs.js" type="text/javascript"></script>

<script>

    $('.use-select2').select2({
        closeOnSelect: false
    });
    
    
</script>

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
@endsection


@section('content')
    <?php
        $owner_type = $owner_type ?? 'user';
        $owner_id = $owner_id ?? access()->user()->id;
        $avatar_url = ($owner_type != 'org') ? access()->user()->avatar_url() : $owner_url;
        $action_url = route('frontend.logo.upload', $organization);
    ?>
    @include('partials.avatar-logic')
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Organization</h4>
                    <div class="heading-elements">
                        @include('partials.admin.org-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    @include('partials.spinner')
                    <div class="card-block">

                    {{ Form::model($organization, ['route' => ['admin.access.org.update', $organization], 'class' => 'form', 'role' => 'form', 'method' => 'patch', 'id' => 'js-org-create-main']) }}

                    <div class="row">
                        <div class="col-xl-8 offset-xl-2">
                            <div class="form-body">

                                @php($build_fields = $organization_fields)
                                @include('partials.event.block-build-fields')

                                {{ Form::hidden('lat_lng', null, ['id' => 'js-org-coord']) }}

                                <div class="form-group">
{{--                                     <div class="row">
                                        <div class="col-md-4"> --}}
                                            <label>Logo</label>
{{--                                         </div>
                                        <div class="col-md-8"> --}}
                                            @include('partials.avatar-ui')
                                        {{-- </div> --}}

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-8 offset-xl-2">
                            <div class="form-actions">

                                <div class="pull-left">
                                    {{ link_to_route('admin.access.org.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
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



@endsection

