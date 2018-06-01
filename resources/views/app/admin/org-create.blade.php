@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Organization Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script async defer 
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQaVZgBql0Qw7hBJJMrlybFenyb5RfcE8">
</script>

<script src="/assets/js/admin/orgs.js" type="text/javascript"></script>

<script>

    $('.use-select2').select2({
        closeOnSelect: false
    });
    
    
</script>
@endsection


@section('content')
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

                    {{ Form::open(['route' => 'admin.access.org.store', 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-org-create-main']) }}

                    <div class="row">
                        <div class="col-xl-8 offset-xl-2">
                            <div class="form-body">

                                @php($build_fields = $organization_fields)
                                @include('partials.event.block-build-fields')

                                {{ Form::hidden('lat_lng', null, ['id' => 'js-org-coord']) }}

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

