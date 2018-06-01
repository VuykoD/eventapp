<div class="pull-right mb-10 hidden-sm-down">
    {{ link_to_route('admin.access.settings_vendor_category.index', trans('menus.backend.access.settings_vendor_category.all'), [], ['class' => 'btn btn-primary btn-xs']) }}
    {{ link_to_route('admin.access.settings_vendor_category.create', trans('menus.backend.access.settings_vendor_category.create'), [], ['class' => 'btn btn-success btn-xs']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-md-up">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.access.settings_vendor_category.main') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.access.settings_vendor_category.index', trans('menus.backend.access.settings_vendor_category.all')) }}</li>
            <li>{{ link_to_route('admin.access.settings_vendor_category.create', trans('menus.backend.access.settings_vendor_category.create')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>