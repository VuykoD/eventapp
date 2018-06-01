@extends('app')

@section('page-header')
    <h2 class="content-header-title">Dashboard</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/clndr.min.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/tables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/tables/datatable/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/moment.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/underscore-min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/extensions/clndr.min.js" type="text/javascript"></script>

<script src="/assets/js/event/dashboard.js" type="text/javascript"></script>

<script>
    var events = {!! json_encode($event_list) !!};
    for (var idx = 0; idx < events.length; idx++) {
        events[idx].date = moment(events[idx].date).format("MM/DD/YYYY");
    }
    window.event_calendar = $("#calendar").clndr({ 
        template: $("#clndr-template").html(), 
        events: events, 
        showAdjacentMonths: true, 
        adjacentDaysChangeMonth: true,
        clickEvents: {
            click: function(target) {
              //the target object is a reference to the day you clicked and
              //it also has an array of all the events of that specific day. Do something with it.
              console.log(target);
              $(target.element).siblings('.day').removeClass('selected');
              $(target.element).toggleClass('selected');
              window.clndr_select_date(target, window.event_calendar);
            }  
        },
        doneRendering: function () {
            $('.event-scroller').height($('.clndr-grid').height() - $('.days-of-the-week').height() - 1);
        },
    });
</script>

@if(access()->user()->hasRoles([config('event.vendor.role.id')]))
<script>
    $.fn.dataTable.ext.errMode = function( settings, techNote, message ) {
        toastr.error(message);
    }
    $(function() {
        $.fn.dataTable.ext.search.push(function( settings, data, dataIndex ) {
            var search_radius = parseInt($('#js-select-radius-filter').val(), 10);
            var radius = parseInt(data[5]);
            if (isNaN(radius) || isNaN(search_radius)) {
                return false;
            }
            return radius < search_radius ? true : false;
        });

        $('#events-radius-table').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                headers: { 'X-CSRF-TOKEN': Laravel.csrfToken },
                url: '{{ route("frontend.radius-event.get") }}',
                type: 'post',
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'event_date', name: 'events.event_date', searchable: false},
                {data: 'start_time', name: 'events.start_time', searchable: false},
                {data: 'coordinator', name: 'coordinator', searchable: false},
                {data: 'status_text', name: 'status_text', searchable: false},
                {data: 'radius', name: 'radius', visible: false},
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ],
            order: [[0, "asc"]],
            searchDelay: 500
        });

        // table.draw();

        // $.fn.dataTable.ext.search.push(function( settings, data, dataIndex ) {
        //     console.log(data);
        //     console.log('Filter');
        //     return false;
        // });


        $('#js-select-radius-filter').on('change', function() {
            $('#events-radius-table').DataTable().draw();
        });

        // $('#js-select-radius-filter').change();
    });
</script>
@endif

@endsection

@section('content')

<div id="clndr" class="clearfix">
    <script type="text/template" id="clndr-template">
        <div class="clndr-controls">
            <div class="clndr-previous-button">&lt;</div>
            <div class="clndr-next-button">&gt;</div>
            <div class="current-month">
                <%= month %>
                <%= year %>
            </div>

        </div>
        <div class="clndr-grid">
            <div class="days-of-the-week clearfix">
                <% _.each(daysOfTheWeek, function(day) { %>
                    <div class="header-day">
                        <%= day %>
                    </div>
                <% }); %>
            </div>
            <div class="days">
                <% _.each(days, function(day) { %>
                    <div class="<%= day.classes %>" id="<%= day.id %>"><span class="day-number"><%= day.day %></span></div>
                <% }); %>
            </div>
        </div>
        <div class="event-listing">
            <div class="event-listing-title">Events This Month</div>
            <div class="event-scroller" id="js-event-scroller">
            <% _.each(eventsThisMonth, function(event) { %>
                <div class="event-item font-small-3" data-event-id="<%= event.id %>" data-event-route="<%= event.route %>" data-event-date="<%= event.date %>">
                    <div class="event-item-day font-small-2">
                        <%= event.date %>
                    </div>
                    <div class="event-item-name text-bold-600">
                        <%= event.title %>
                    </div>
                    <div class="event-item-location">
                        <%= event.location %>
                    </div>
                </div>
            <% }); %>
            </div>
        </div>
    </script>
    <script type="text/template" id="clndr-all-events-listing">
        
    </script>
</div>

<section class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Welcome {{ $logged_in_user->full_name }}!</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">
                        <div id="calendar" class="use-clndr overflow-hidden bg-grey bg-lighten-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        {{-- Vendor specific --}}

@if(access()->user()->hasRoles([config('event.vendor.role.id')]))

<section class="row">
    <div class="col-sm-12">
        <div class="card">
            @include('partials.spinner', ['spinner_location' =>  'confirm-vendor'])
            <div class="card-header">
                <h4 class="card-title">Respond to invitation</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">
                        {{ Form::open(['route' => ['frontend.vendor.confirm.invite', '1xxx1'], 'class' => 'form', 'role' => 'form', 'id' => 'js-form-confirm-invitation']) }}
                        
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    {{ Form::label('confirm_vendor', 'Please enter 5-digit event code from invitation email', []) }}
                                    {{ Form::text('confirm_vendor', null, ['class' => 'form-control', 'id' => 'js-confirm-vendor-code', 'required' => '']) }}
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label >&nbsp;</label>
                                    <button type="submit" class="btn btnc-primary btn-block">Submit</button>
                                </div>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <div class="card">
            {{-- @include('partials.spinner', ['spinner_location' =>  'search-upcoming']) --}}
            <div class="card-header">
                <h4 class="card-title">Available upcoming events</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text table-responsive">

                        <div class="row">
                            <div class="col-sm-4 pull-right table-search-block">
                                <label>Radius</label>
                                <select class="form-control input-sm" id="js-select-radius-filter">
                                    <option value="25" selected>25 miles</option>
                                    <option value="50">50 miles</option>
                                    <option value="100">100 miles</option>
                                    <option value="250">250 miles</option>
                                </select>  
                            </div>
                        </div>
                        <table id="events-radius-table" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Event Date</th>
                                    <th>Start Time</th>
                                    <th>Coordinator</th>
                                    <th>Status</th>
                                    <th>Radius</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                            

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endif

        {{-- Host specific --}}

@if(access()->user()->hasRoles([config('event.host.role.id')]))

<section class="row">
    <div class="col-sm-12">
        <div class="card">
            @include('partials.spinner', ['spinner_location' =>  'confirm-host'])
            <div class="card-header">
                <h4 class="card-title">Respond to host invitation</h4>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">
                        {{ Form::open(['route' => ['frontend.host.confirm', '1xxx1'], 'class' => 'form', 'role' => 'form', 'id' => 'js-form-confirm-host', 'method' => 'get']) }}
                        
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    {{ Form::label('confirm_host', 'Please enter 5-digit code from notification email', []) }}
                                    {{ Form::text('confirm_host', null, ['class' => 'form-control', 'id' => 'js-confirm-host-code', 'required' => '']) }}
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label >&nbsp;</label>
                                    <button type="submit" class="btn btnc-primary btn-block" id="js-button-confirm-host">Submit</button>
                                </div>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endif

@endsection