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
<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js" type="text/javascript"></script>
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
        $('#js-form-confirmed').on('change', function(e) {
            var $do_email = $('#js-form-do-email');
            if ($(this).is(':checked')) {
                $do_email.prop('disabled', true);
                $do_email.prop('checked', false);
                return;
            }
            $do_email.prop('disabled', false);
        });
        $('#js-form-confirmed').change();
    });

    $('.use-select2').select2();
    $('.switchery').each(function(){
        new Switchery(this);
    });

    $('#js-form-create-user').validate({
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
                <div class="card-header">
                    <h4 class="card-title">Create User</h4>
                    <div class="heading-elements">
                        @include('partials.admin.user-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard">

                    {{ Form::open(['route' => 'admin.access.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-create-user']) }}

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

                        <div class="form-group">
                            {{ Form::label('email', trans('validation.attributes.backend.access.users.email'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-9">
                                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.email'), 'required' => '']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        @include('partials.new_persona')

                        <div class="form-group">
                            {{ Form::label('password', trans('validation.attributes.backend.access.users.password'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-9">
                                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.password'), 'required' => '']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        <div class="form-group">
                            {{ Form::label('password_confirmation', trans('validation.attributes.backend.access.users.password_confirmation'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-9">
                                {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.password_confirmation'), 'required' => '', 'data-rule-equalTo' => '#password']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        <div class="form-group">
                            {{ Form::label('status', trans('validation.attributes.backend.access.users.active'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-1">
                                {{ Form::checkbox('status', '1', true) }}
                            </div><!--col-lg-1-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        <div class="form-group">
                            {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-1">
                                {{ Form::checkbox('confirmed', '1', true, ['id' => 'js-form-confirmed']) }}
                            </div><!--col-lg-1-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">{{ trans('validation.attributes.backend.access.users.send_confirmation_email') }}<br/>
                                <small>{{ trans('strings.backend.access.users.if_confirmed_off') }}</small>
                            </label>

                            <div class="col-lg-1">
                                {{ Form::checkbox('confirmation_email', '1', false, ['id' => 'js-form-do-email']) }}
                            </div><!--col-lg-1-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                    <div class="hidden">

                        <div class="form-group">
                            {{ Form::label('status', trans('validation.attributes.backend.access.users.associated_roles'), ['class' => 'col-lg-3 control-label']) }}

                            <div class="col-lg-9">
                                @if (count($roles) > 0)
                                    @foreach($roles as $role)

                                        @if ($role->id == 1)
                                            @continue
                                        @endif

                                        <input type="checkbox" value="{{ $role->id }}" name="assignees_roles[{{ $role->id }}]" id="role-{{ $role->id }}" {{ is_array(old('assignees_roles')) && in_array($role->id, old('assignees_roles')) ? 'checked' : '' }} /> <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                                        <a href="#" data-role="role_{{ $role->id }}" class="show-permissions small">
                                            (
                                                <span class="show-text">{{ trans('labels.general.show') }}</span>
                                                <span class="hide-text hidden">{{ trans('labels.general.hide') }}</span>
                                                {{ trans('labels.backend.access.users.permissions') }}
                                            )
                                        </a>
                                        <br/>
                                        <div class="permission-list hidden" data-role="role_{{ $role->id }}">
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

                    </div>
    
                        <div class="pull-left">
                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                        </div><!--pull-left-->

                        <div class="pull-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
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

