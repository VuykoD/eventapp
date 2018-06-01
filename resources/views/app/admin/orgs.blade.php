@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Organization Management</h2>
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
            $('#orgs-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    headers: { 'X-CSRF-TOKEN': Laravel.csrfToken },
                    url: '{{ route("admin.access.org.get") }}',
                    type: 'post'
                },
                columns: [
                    {data: 'name', name: 'organizations.name'},
                    {data: 'address', name: 'org.address'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, "asc"]],
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
                    <h4 class="card-title">Organizations</h4>
                    <div class="heading-elements">
                        @include('partials.admin.org-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard table-responsive">
                        <table id="orgs-table" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
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
