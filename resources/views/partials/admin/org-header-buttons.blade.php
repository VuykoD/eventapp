@permissions(['view-events'])

<div class="pull-right mb-10 hidden-sm-down">
    {{ link_to_route('admin.access.org.index', 'All Organizations', [], ['class' => 'btn btn-primary btn-xs']) }}
    @if(isset($organization))
    {{ link_to_route('admin.access.org.show', 'View Organization', [$organization->id], ['class' => 'btn btn-info btn-xs']) }}
    {{ link_to_route('admin.access.org.edit', 'Edit Organization', [$organization->id], ['class' => 'btn btn-warning btn-xs']) }}
    @endif
    {{ link_to_route('admin.access.org.create', 'Create Organization', [], ['class' => 'btn btn-success btn-xs']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-md-up">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ 'Events' }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.access.org.index', 'All Organizations') }}</li>
            @if(isset($organization))
            <li>{{ link_to_route('admin.access.org.show', 'View Organization', [$organization->id]) }}</li>
            <li>{{ link_to_route('admin.access.org.edit', 'Edit Organization', [$organization->id]) }}</li>
            @endif
            <li>{{ link_to_route('admin.access.org.create', 'Create Organization') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

@endauth
<div class="clearfix"></div>