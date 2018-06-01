@extends('app')

@section('page-header')
    <h2 class="content-header-title">{{ trans('menus.backend.access.agreement.management') }}</h2>
@endsection

@section('css')
@endsection

@section('javascript')

<script src="/assets/js/plugins/forms/validation/jquery.validate.min.js"></script>
<script src="/assets/js/event/tpl-edit.js" type="text/javascript"></script>
<script src="/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script>tinymce.init({ selector:'textarea' });</script>

@endsection

@section('content')

<section class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ trans('menus.backend.access.agreement.edit') }}</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">


                        {{ Form::open(['route' => 'admin.access.agreements.store', 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-agreements-create-main']) }}


                        <div class="form-body">
                        

                            <textarea class="form-control" required="" name="body" cols="50" rows="10"  aria-required="true" aria-invalid="false" style="margin-top: 0px; margin-bottom: 0px; height: 280px;">{{$Agreement[0]->body}}</textarea>

                       </div>

                       <div class="form-actions">
                            <div class="pull-left">
                                {{ link_to_route('admin.access.agreements.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
                            </div>

                            <div class="pull-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs']) }}
                            </div>

                            <div class="clearfix"></div>

                        </div>
                        {{ Form::close() }}
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection