@extends ('app')

@section('page-header')
    <h2 class="content-header-title">User Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/switchery.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/forms/switchery.min.js" type="text/javascript"></script>
<script src="/assets/js/admin/personas.js" type="text/javascript"></script>

<script>
    $(function() {
        $(".show-permissions").click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var role = $this.data('role');
            var permissions = $(".permission-list[data-role='"+role+"']");
            var hideText = $this.find('.hide-text');
            var showText = $this.find('.show-text');
            // console.log(permissions); // for debugging

            // show permission list
            permissions.toggleClass('hidden');

            // toggle the text Show/Hide for the link
            hideText.toggleClass('hidden');
            showText.toggleClass('hidden');
        });
    });

    $('.use-select2').select2();
    $('.switchery').each(function(){
        new Switchery(this);
    });

    $(document).ready(function() {
        $('.vendor-action').on('click', function(e) {
            e.preventDefault();
            var form = $('#js-form-edit-user');
            form.attr('action', $(this).data('route'));
            form.append($('<input type="hidden" name="status" value="1">'));
            form.submit();
        });
    });
</script>
@endsection


@section('content')
    <!-- <pre>
        {{ print_r($user) }}
    </pre> -->
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit User</h4>
                    <div class="heading-elements">
                        @include('partials.admin.user-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard">

                    {{ Form::model($user, ['route' => ['admin.access.user.update', $user], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'js-form-edit-user']) }}

                    <div class="form-body">

                        <div class="form-group">
                            {{ Form::label('persona[first_name]', 'First Name', ['class' => 'col-lg-3 control-label', 'style' => 'padding-top: 4px;']) }}

                            <div class="col-lg-3">
                                {{ Form::text('persona[first_name]', null, ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => '']) }}
                            </div>

                            {{ Form::label('persona[last_name]', 'Last Name', ['class' => 'col-lg-3 control-label', 'style' => 'padding-top: 4px;']) }}

                            <div class="col-lg-3">
                                {{ Form::text('persona[last_name]', null, ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => '']) }}
                            </div>
                        </div>

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        {{-- Organization --}}

                        @if ($user->has_org)

                            @php ($organization_list = ['' => ''] + $organization_list)

                            <div class="form-group">
                                {{ Form::label('organization_id', 'Select Organization', ['class' => 'col-lg-3 control-label']) }}
                                <div class="col-lg-9">
                                    {{ Form::select('organization_id', $organization_list, $user->persona->org_id, ['class' => 'form-control use-select2', 'id' => 'js-select-user-org']) }}
                                </div>
                            </div>

                            <div class="clearfix hidden-md-down row-spacer"></div>

                        @endif

                        <div class="form-group">
                            {{ Form::label('email', trans('validation.attributes.backend.access.users.email'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-9">
                                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.email')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>
                        
                        @include('partials.edit_persona')

                        @if ($user->id != config('event.admin.id'))
                            @if ($user->hasRole('Vendor User') && !$user->isActive())
                            <button type="button" id="approve-vendor" class="btn btn-primary btn-xs vendor-action" data-route="{{ route('admin.access.vendor.approve', $user) }}">Approve</button>
                            <button type="button" id="deny-vendor" class="btn btn-warning btn-xs vendor-action" data-route="{{ route('admin.access.vendor.deny', $user) }}">Deny</button>
                            @else
                            <div class="form-group">
                                {{ Form::label('status', trans('validation.attributes.backend.access.users.active'), ['class' => 'col-lg-3 control-label']) }}

                                <div class="col-lg-1">
                                    {{ Form::checkbox('status', '1', $user->status == 1) }}
                                </div><!--col-lg-1-->
                            </div><!--form control-->
                            @endif

                            <div class="clearfix hidden-md-down row-spacer"></div>

                            <div class="form-group">
                                {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed'), ['class' => 'col-lg-3 control-label']) }}

                                <div class="col-lg-1">
                                    {{ Form::checkbox('confirmed', '1', $user->confirmed == 1) }}
                                </div><!--col-lg-1-->
                            </div><!--form control-->

                            <div class="clearfix hidden-md-down row-spacer"></div>

                            <div class="form-group hidden">
                                {{ Form::label('status', trans('validation.attributes.backend.access.users.associated_roles'), ['class' => 'col-lg-3 control-label']) }}

                                <div class="col-lg-9">
                                    @if (count($roles) > 0)
                                        @foreach($roles as $role)
                                        
                                            @if ($role->id == 1)
                                                @continue
                                            @endif

                                            <input type="checkbox" value="{{$role->id}}" name="assignees_roles[{{ $role->id }}]" {{ is_array(old('assignees_roles')) ? (in_array($role->id, old('assignees_roles')) ? 'checked' : '') : (in_array($role->id, $user_roles) ? 'checked' : '') }} id="role-{{$role->id}}" /> <label for="role-{{$role->id}}">{{ $role->name }}</label>
                                                <a href="#" data-role="role_{{$role->id}}" class="show-permissions small">
                                                    (
                                                        <span class="show-text">{{ trans('labels.general.show') }}</span>
                                                        <span class="hide-text hidden">{{ trans('labels.general.hide') }}</span>
                                                        {{ trans('labels.backend.access.users.permissions') }}
                                                    )
                                                </a>
                                            <br/>
                                            <div class="permission-list hidden" data-role="role_{{$role->id}}">
                                                @if ($role->all)
                                                    {{ trans('labels.backend.access.users.all_permissions') }}<br/><br/>
                                                @else
                                                    @if (count($role->permissions) > 0)
                                                        <blockquote class="small">{{--
                                                    --}}@foreach ($role->permissions as $perm){{--
                                                    --}}{{$perm->display_name}}<br/>
                                                            @endforeach
                                                        </blockquote>
                                                    @else
                                                        {{ trans('labels.backend.access.users.no_permissions') }}<br/><br/>
                                                    @endif
                                                @endif
                                            </div><!--permission list-->
                                        @endforeach
                                    @else
                                        {{ trans('labels.backend.access.users.no_roles') }}
                                    @endif
                                </div><!--col-lg-3-->
                            </div><!--form control-->

                            <div class="clearfix hidden-md-down row-spacer"></div>
                        @endif


                        @if ($user->id == config('event.admin.id'))
                            {{ Form::hidden('status', 1) }}
                            {{ Form::hidden('confirmed', 1) }}
                            {{ Form::hidden('assignees_roles[]', 1) }}
                        @endif
                        
                        <div class="pull-left">
                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                        </div><!--pull-left-->

                        <div class="pull-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs']) }}
                        </div><!--pull-right-->

                        <div class="clearfix"></div>

                    </div>

                    {{ Form::close() }}

                </div>
                </div>
            </div>
        </div>
    </section>
@endsection


