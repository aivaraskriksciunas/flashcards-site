@extends( 'templates.dash' )

@section( 'title', 'Add deck' )

@section( 'content' )
    
<x-page-title>
    {{ $deck->name }}
</x-page-title>

<p>Deck belongs to user <a href='{{ route( 'user.show', $user ) }}'>{{ $user->name }}</a> ({{ $user->email }}).</p>

<p>Created at: {{ $deck->created_at }}.</p>

<div class='list-group mb-3'>
    @foreach ( $deck->cards as $card )

        <div class='list-group-item list-group-item-action d-flex w-100 align-items-center'>
            <div class='position-relative flex-grow-1'>
                <div class='fw-bold'>{{ $card->question }}</div>
                <div>{{ $card->answer }}</div>
            </div>
        </div>

    @endforeach
</div>

<a href='{{ route( 'user.show', $user ) }}' class='btn btn-secondary'>Back to user</a>

@endsection