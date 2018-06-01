<table class="table table-striped table-hover table-responsive">
    <tr>
        <th>{{ trans('labels.frontend.user.profile.avatar') }}</th>
        <td><img src="{{ access()->user()->avatar_url() }}" class="user-profile-image" /></td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.name') }}</th>
        <td>{{ $logged_in_user->name }}</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.email') }}</th>
        <td>{{ $logged_in_user->email }}</td>
    </tr>
    @if(access()->user()->allow('manage-users'))
    <tr>
        <th>{{ trans('labels.frontend.user.profile.created_at') }}</th>
        <td>{{ $logged_in_user->created_at->format('m/d/y') }} ({{ $logged_in_user->created_at->diffForHumans() }})</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.last_updated') }}</th>
        <td>{{ $logged_in_user->updated_at->format('m/d/y') }} ({{ $logged_in_user->updated_at->diffForHumans() }})</td>
    </tr>
    @endif
    @if(!access()->user()->allow('manage-users'))
    <tr>
        <th>Created</th>
        <td>{{ $logged_in_user->created_at->diffForHumans() }}</td>
    </tr>
    @endif
</table>