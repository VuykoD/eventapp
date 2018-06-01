@permissions(['manage-all-events', 'manage-own-events'])

<div class="pull-right mb-10 hidden-sm-down">
    {{ link_to_route('frontend.event.index', 'All Events', [], ['class' => 'btn btn-primary btn-xs']) }}
    @if(isset($event))
    {{ link_to_route('frontend.event.show', 'View Event', [$event->id], ['class' => 'btn btn-info btn-xs']) }}
    {{ link_to_route('frontend.event.edit', 'Edit Event', [$event->id], ['class' => 'btn btn-warning btn-xs']) }}
    {{ link_to_route('frontend.event.vendors.update', 'Vendors', [$event->id], ['class' => 'btn btn-success btn-xs']) }}
    {{ link_to_route('frontend.event.notes.show', 'Notes', [$event->id], ['class' => 'btn btn-primary btn-xs']) }}
    @endif
    {{ link_to_route('frontend.event.create', 'Create Event', [], ['class' => 'btn btn-success btn-xs']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-md-up">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ 'Events' }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('frontend.event.index', 'All Events') }}</li>
            @if(isset($event))
            <li>{{ link_to_route('frontend.event.show', 'View Event', [$event->id]) }}</li>
            <li>{{ link_to_route('frontend.event.edit', 'Edit Event', [$event->id]) }}</li>
            <li>{{ link_to_route('frontend.event.vendors.update', 'Vendors', [$event->id]) }}</li>
            <li>{{ link_to_route('frontend.event.notes.show', 'Notes', [$event->id]) }}</li>
            @endif
            <li>{{ link_to_route('frontend.event.create', 'Create Event') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

@endauth
<div class="clearfix"></div>