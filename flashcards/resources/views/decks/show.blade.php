@extends( 'templates.dash' )

@section( 'title', 'Add deck' )

@section( 'content' )
    
<x-page-title>
    {{ $deck->name }}
</x-page-title>

<p>Deck belongs to user <a href='{{ route( 'user.show', $user ) }}'>{{ $user->name }}</a> ({{ $user->email }}).</p>

<p>Created at: {{ $deck->created_at }}.</p>

<a href='{{ route( 'user.show', $user ) }}' class='btn btn-secondary'>Back to user</a>

@endsection