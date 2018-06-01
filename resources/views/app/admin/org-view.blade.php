@extends ('app')

@section('page-header')
    <h2 class="content-header-title">Organization Management</h2>
@endsection

@section('css')

@endsection

@section('javascript')

@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">View Organization</h4>
                    <div class="heading-elements">
                        @include('partials.admin.org-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                        @include('partials.admin.block-org-view')    

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

