
<table class="table table-striped table-hover">
    
    <tr>
        <th>Name</th>
        <td>{{ $event->name }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $event->status_text }}</td>
    </tr>

    @permissions(['manage-all-events', 'manage-own-events'])

    <tr>
        <th>View URL</th>
        <td><a href="{{ route('frontend.event.view.public', $event->slug) }}" target="_blank">{{ route('frontend.event.view.public', $event->slug) }}</a></td>
    </tr>

    @endauth
    
    <tr>
        <th>Coordinator</th>
        <td>{{ $event->coordinator_name }}</td>
    </tr>
    <tr>
        <th>Host</th>
        <td>{{ $event->host_name }}</td>
    </tr>
    <tr>
        <th>Venue</th>
        <td>{{ $event->venue_name }}</td>
    </tr>
    <tr>
        <th>Event Date</th>
        <td>{{ $event->event_date }}</td>
    </tr>
    <tr>
        <th>Event Starts At</th>
        <td>{{ $event->start_time }}</td>
    </tr>
    <tr>
        <th>Event Ends At</th>
        <td>{{ $event->end_time }}</td>
    </tr>
    <tr>
        <th>Event Type</th>
        <td>{{ $event->type_name }}</td>
    </tr>
    <tr>
        <th>Event Topics</th>
        <td>
            @foreach($event->topics as $topic)
                {{ $topic->name }}{{ !$loop->last ? ', ' : '' }}
            @endforeach 
        </td>
    </tr>

    @permissions(['manage-all-events', 'manage-own-events'])

    @foreach($event->categories as $catg)
        <tr>
            <th>Vendor Category</th>
            <td>{{ $catg->name }}</td>
        </tr>
        <tr>
            <th class="offset-row">Vendor Slots</th>
            <td>{{ $catg->pivot->slots }}</td>
        </tr>
        @foreach($event->getVendorsByCategory($catg->id) as $vendor)
            <tr>
                <th class="offset-row">Vendor <small>({{ config('event.vendor.status_text')[$vendor['status']] }})</small></th>
                <td>{{ $vendor['full_name'] }}</td>
            </tr>
        @endforeach
    @endforeach

    @if($event->status == config('event.status.completed'))
        
        <tr>
            <th>Number of Attendees</th>
            <td>{{ $event->num_attendees }}</td>
        </tr>
        <tr>
            <th>Attendance</th>
            <td>{{ $event->attendance }}</td>
        </tr>
        <tr>
            <th>Comments</th>
            <td>{!! $event->complete_text !!}</td>
        </tr>

        @foreach($event->feedbacks_for_view as $feedback)
    
            <tr>
                <th>Feedback for Vendor</th>
                <td>{{ $feedback['vendor'] }}</td>
            </tr>
            <tr>
                <th class="offset-row">Rating</th>
                <td>{{ $feedback['rating'] }}</td>
            </tr>
            <tr>
                <th class="offset-row">Attendance</th>
                <td>{{ $feedback['attendance'] }}</td>
            </tr>
            <tr>
                <th class="offset-row">Comments</th>
                <td>{!! $feedback['comments'] !!}</td>
            </tr>

        @endforeach

    @endif

    @endauth

</table>