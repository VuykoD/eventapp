
<table class="table table-striped table-hover">
    <tr>
        <th>Name</th>
        <td>{{ $organization->name }}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{ $organization->full_address }}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{ $organization->phone }}</td>
    </tr>
    <tr>
        <th>Website</th>
        <td>{{ $organization->website }}</td>
    </tr>
</table>