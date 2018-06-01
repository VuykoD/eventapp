@extends ('app')

@section('page-header')
    <h2 class="content-header-title">User Management</h2>
@endsection


@section('content')
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">View User</h4>
                    <div class="heading-elements">
                        @include('partials.admin.user-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block card-dashboard">


                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-underline" role="tablist">
                            <li role="presentation" class="nav-item">
                                <a <a class="nav-link active" href="#overview" aria-controls="overview" role="tab" data-toggle="tab">{{ trans('labels.backend.access.users.tabs.titles.overview') }}</a>
                            </li>

                            
                        </ul>

                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane mt-30 active" id="overview">
                                @include('partials.tabs.user_overview')
                            </div><!--tab overview profile-->

                            

                        </div><!--tab content-->



                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection