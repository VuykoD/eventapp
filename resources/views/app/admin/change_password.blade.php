@extends ('app')

@section('page-header')
    <h2 class="content-header-title">User Management</h2>
@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Change Password for {{ $user->name }}</h4>
                    <div class="heading-elements">
                        @include('partials.admin.user-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard">

                    {{ Form::open(['route' => ['admin.access.user.change-password', $user], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'patch']) }}

                    <div class="form-body">
                        
                        <div class="form-group">
                            {{ Form::label('password', trans('validation.attributes.backend.access.users.password'), ['class' => 'col-lg-3 control-label', 'placeholder' => trans('validation.attributes.backend.access.users.password')]) }}

                            <div class="col-lg-9">
                                {{ Form::password('password', ['class' => 'form-control']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

                        <div class="form-group">
                            {{ Form::label('password_confirmation', trans('validation.attributes.backend.access.users.password_confirmation'), ['class' => 'col-lg-3 control-label', 'placeholder' => trans('validation.attributes.backend.access.users.password_confirmation')]) }}

                            <div class="col-lg-9">
                                {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="clearfix hidden-md-down row-spacer"></div>

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