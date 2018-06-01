@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Role Management</h2>
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
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    headers: { 'X-CSRF-TOKEN': Laravel.csrfToken },
                    url: '{{ route("admin.access.role.get") }}',
                    type: 'post'
                },
                columns: [
                    {data: 'name', name: '{{config('access.roles_table')}}.name'},
                    {data: 'permissions', name: '{{config('access.permissions_table')}}.display_name', sortable: false},
                    {data: 'users', name: 'users', searchable: false, sortable: false},
                    {data: 'sort', name: '{{config('access.roles_table')}}.sort'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[3, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Roles</h4>
                    <div class="heading-elements">
                        @include('partials.admin.role-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard table-responsive">
                        <table id="roles-table" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('labels.backend.access.roles.table.role') }}</th>
                                    <th>{{ trans('labels.backend.access.roles.table.permissions') }}</th>
                                    <th>{{ trans('labels.backend.access.roles.table.number_of_users') }}</th>
                                    <th>{{ trans('labels.backend.access.roles.table.sort') }}</th>
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
