@extends ('app')

@section('page-header')
    <h2 class="content-header-title">{{ trans('menus.backend.access.settings_event_category.management') }}</h2>
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
                    <h4 class="card-title">
                        @if($data==null){{ trans('menus.backend.access.settings_event_category.create') }}@endif
                        @if(!$data==null){{ trans('menus.backend.access.settings_event_category.edit') }}@endif
                    </h4>
                    <div class="heading-elements">
                        @include('partials.admin.event-category-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    @include('partials.spinner')
                    <div class="card-block">

                    {{ Form::open(['route' => 'admin.access.settings_event_category.store', 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-settings_event_category-create-main']) }}

                    <div class="row">
                        <div class="col-xl-8 offset-xl-2">
                            <div class="form-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input class="form-control" type="hidden"  name="id" type="text" id="id" value="@if(!$data==null){{$data[0]->id}}@endif">
                                    <input class="form-control" placeholder="Name" required="" name="name" type="text" id="name" value="@if(!$data==null){{$data[0]->name}}@endif">
                                </div>
                                <div class="form-group">
                                    <label for="name">Code</label>
                                    <input class="form-control" placeholder="Code"  name="code" type="text" id="code" value="@if(!$data==null){{$data[0]->code}}@endif">
                                </div>
                                <div class="form-group">
                                    <label for="name">Custom</label>
                                    <input class="form-control" placeholder="Custom" required="" name="custom" type="text" id="custom"  value="@if(!$data==null){{$data[0]->custom}}@endif">
                                </div>
                                {{ Form::hidden('lat_lng', null, ['id' => 'js-settings_event_category-coord']) }}

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-8 offset-xl-2">
                            <div class="form-actions">

                                <div class="pull-left">
                                    {{ link_to_route('admin.access.settings_event_category.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
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

