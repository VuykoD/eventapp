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
                    <h4 class="card-title">{{ $sub_title ?? '' }}</h4>
                    <div class="heading-elements">
                        @include('partials.event.event-header-buttons')
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">

                        @if($action == config('event.action.vendor.confirm'))
                            {{ $invite->success ? 'Thank you, your participation was confirmed' : 'Code not found' }}
                        @endif 

                        @if($action == config('event.action.vendor.decline'))
                            {{ $invite->success ? 'Thank you for letting us know' : 'Code not found' }}
                        @endif 

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

