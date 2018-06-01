@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Role Management</h2>
@endsection

@section('javascript')
<script>
    var associated = $("select[name='associated-permissions']");
    var associated_container = $("#available-permissions");

    if (associated.val() == "custom")
        associated_container.removeClass('hidden');
    else
        associated_container.addClass('hidden');

    associated.change(function() {
        if ($(this).val() == "custom")
            associated_container.removeClass('hidden');
        else
            associated_container.addClass('hidden');
    });
</script>
@endsection


@section('content')
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Role</h4>
                    <div class="heading-elements">
                        @include('partials.admin.role-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard">

                        {{ Form::open(['route' => 'admin.access.role.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) }}

                        <div class="form-body">
                        
                            <div class="form-group">
                                {{ Form::label('name', trans('validation.attributes.backend.access.roles.name'), ['class' => 'col-lg-3 control-label']) }}

                                <div class="col-lg-9">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.roles.name')]) }}
                                </div><!--col-lg-10-->
                            </div><!--form control-->

                            <div class="clearfix hidden-md-down row-spacer"></div>

                            <div class="form-group">
                                {{ Form::label('associated-permissions', trans('validation.attributes.backend.access.roles.associated_permissions'), ['class' => 'col-lg-3 control-label']) }}

                                <div class="col-lg-9">
                                    {{ Form::select('associated-permissions', array('all' => trans('labels.general.all'), 'custom' => trans('labels.general.custom')), 'all', ['class' => 'form-control']) }}

                                    <div id="available-permissions" class="hidden mt-20">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                @if ($permissions->count())
                                                    @foreach ($permissions as $perm)
                                                        <input type="checkbox" name="permissions[{{ $perm->id }}]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" {{ is_array(old('permissions')) && in_array($perm->id, old('permissions')) ? 'checked' : '' }} /> <label for="perm_{{ $perm->id }}">{{ $perm->display_name }}</label><br/>
                                                    @endforeach
                                                @else
                                                    <p>There are no available permissions.</p>
                                                @endif
                                            </div><!--col-lg-6-->
                                        </div><!--row-->
                                    </div><!--available permissions-->
                                </div><!--col-lg-3-->
                            </div><!--form control-->

                            <div class="clearfix hidden-md-down row-spacer"></div>

                            <div class="form-group ">
                                {{ Form::label('sort', trans('validation.attributes.backend.access.roles.sort'), ['class' => 'col-lg-3 control-label']) }}

                                <div class="col-lg-2">
                                    {{ Form::text('sort', ($role_count+1), ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.roles.sort')]) }}
                                </div><!--col-lg-10-->
                            </div><!--form control-->
                      
                            <div class="clearfix hidden-md-down row-spacer"></div>

                            <div class="col-sm-12">
                                <div class="pull-left">
                                    {{ link_to_route('admin.access.role.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                                </div><!--pull-left-->

                                <div class="pull-right">
                                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                                </div><!--pull-right-->
                            </div>

                            <div class="clearfix"></div>

                        </div>

                        {{ Form::close() }}
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

