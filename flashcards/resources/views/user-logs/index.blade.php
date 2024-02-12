@extends( 'templates.dash' )

@section( 'title', 'User activity' )

@section( 'content' )

<x-page-title>
    Activity logs 
</x-page-title>

<p>Showing activity logs for user '{{ $user->name }}' ({{ $user->email }})</p>

<table class='table table-striped'>
    <tr>
        <th>#</th>
        <th>Action</th>
        <th>Related model</th>
        <th>Date</th>
        <th>More</th>
    </tr>

    @foreach ( $logs as $record )
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $record->action }}</td>
            <td>{{ $record->object_id }}</td>
            <td>{{ $record->created_at }}</td>
            <td>
                <a href='{{ route( 'user-logs.show', $record->id ) }}'>+</a>
            </td>
        </tr>
    @endforeach
</table>

{{ $logs->links() }}

@endsection