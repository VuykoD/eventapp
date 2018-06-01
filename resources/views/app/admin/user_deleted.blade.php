@extends ('app')

@section('page-header')
    <h2 class="content-header-title">User Management</h2>
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
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                headers: { 'X-CSRF-TOKEN': Laravel.csrfToken },
                url: '{{ route("admin.access.user.get") }}',
                type: 'post',
                data: {status: false, trashed: true}
            },
            columns: [
                {data: 'id', name: '{{config('access.users_table')}}.id'},
                {data: 'name', name: '{{config('access.users_table')}}.name'},
                {data: 'email', name: '{{config('access.users_table')}}.email'},
                {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                {data: 'roles', name: '{{config('access.roles_table')}}.name', sortable: false},
                {data: 'created_at', name: '{{config('access.users_table')}}.created_at'},
                {{-- {data: 'updated_at', name: '{{config('access.users_table')}}.updated_at'}, --}}
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ],
            order: [[0, "asc"]],
            searchDelay: 500
        });

        $("body").on("click", "a[name='delete_user_perm']", function(e) {
            e.preventDefault();
            var linkURL = $(this).attr("href");

            swal({
                title: "{{ trans('strings.backend.general.are_you_sure') }}",
                text: "{{ trans('strings.backend.access.users.delete_user_confirm') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('strings.backend.general.continue') }}",
                cancelButtonText: "{{ trans('buttons.general.cancel') }}",
                closeOnConfirm: false
            }, function(isConfirmed){
                if (isConfirmed){
                    window.location.href = linkURL;
                }
            });
        });

        $("body").on("click", "a[name='restore_user']", function(e) {
            e.preventDefault();
            var linkURL = $(this).attr("href");

            swal({
                title: "{{ trans('strings.backend.general.are_you_sure') }}",
                text: "{{ trans('strings.backend.access.users.restore_user_confirm') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('strings.backend.general.continue') }}",
                cancelButtonText: "{{ trans('buttons.general.cancel') }}",
                closeOnConfirm: false
            }, function(isConfirmed){
                if (isConfirmed){
                    window.location.href = linkURL;
                }
            });
        });
    });
</script>

@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Deleted Users</h4>
                    <div class="heading-elements">
                        @include('partials.admin.user-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard table-responsive">
                        <table id="users-table" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('labels.backend.access.users.table.id') }}</th>
                                    <th>{{ trans('labels.backend.access.users.table.name') }}</th>
                                    <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                                    <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                                    <th>{{ trans('labels.backend.access.users.table.roles') }}</th>
                                    <th>{{ trans('labels.backend.access.users.table.created') }}</th>
                                    {{-- <th>{{ trans('labels.backend.access.users.table.last_updated') }}</th> --}}
                                    <th>{{ trans('labels.general.actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

