@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Event Management</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/sweetalert.css">
@endsection

@section('javascript')
<script src="/assets/js/plugins/tables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/js/plugins/tables/datatable/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="/assets/js/sweetalert.min.js" type="text/javascript"></script>
<script src="/assets/js/alerts.js" type="text/javascript"></script>

<script>
    $.fn.dataTable.ext.errMode = function( settings, techNote, message ) {
        toastr.error(message);
    }
    $(function() {
        $('#events-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                headers: { 'X-CSRF-TOKEN': Laravel.csrfToken },
                url: '{{ route("frontend.event.get") }}',
                type: 'post',
            },
            columns: [
                {data: 'name', name: 'events.name'},
                {data: 'event_date', name: 'events.event_date'},
                {data: 'start_time', name: 'events.start_time'},
                {data: 'coordinator', name: 'coordinator'},
                {data: 'host', name: 'host'},
                {data: 'status_text', name: 'status_text'},
                {data: 'completed', name: 'completed', visible: false},
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ],
            order: [[0, "asc"]],
            searchDelay: 500
        });

        $('#js-select-event-filter').on('change', function() {
            $('#events-table').DataTable().column('completed:name').search($(this).val()).draw();
        });

        $('#js-select-event-filter').change();
    });
</script>

@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Events</h4>
                    <div class="heading-elements">
                        @include('partials.event.event-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard table-responsive">
                        <div class="row">
                            <div class="col-sm-4 pull-right table-search-block">
                                <label>Show only</label>
                                <select class="form-control input-sm" id="js-select-event-filter">
                                    <option value="">All events</option>
                                    <option value="0" selected>Upcoming</option>
                                    <option value="1">Completed</option>
                                </select>  
                            </div>
                        </div>
                        <table id="events-table" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Event Date</th>
                                    <th>Start Time</th>
                                    <th>Coordinator</th>
                                    <th>Host</th>
                                    <th>Status</th>
                                    <th>Completed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
