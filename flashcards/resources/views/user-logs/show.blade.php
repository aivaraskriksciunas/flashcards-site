@extends( 'templates.dash' )

@section( 'title', 'User activity' )

@section( 'content' )

<x-page-title>
    Log entry #{{ $entry->id }}
</x-page-title>

<p>Showing activity log entry for user '{{ $user->name }}' ({{ $user->email }}), created at {{ $entry->created_at }}.</p>

<x-logs.log-entry-content :entry="$entry"></x-logs.log-entry-content>

@endsection