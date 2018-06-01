@extends ('app')

@section('page-header')
    @if(isset($logged_in_user))
    <h2 class="content-header-title">Event Management</h2>
    @endif
    @if(!isset($logged_in_user))
    <h2 class="content-header-title">{{ $event->name }}</h2>
    @endif
@endsection

@section('css')

@endsection

@section('javascript')
@if(!isset($logged_in_user))
    <script>
        $('body').removeClass('vertical-content-menu');
    </script>
@endif
@endsection


@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">View Event</h4>
                    <div class="heading-elements">
                        @include('partials.event.event-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                        @include('partials.event.block-event-view')    

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

