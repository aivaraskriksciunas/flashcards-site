@extends( 'templates.dash' )

@section( 'title', 'User details' )

@section( 'content' )
    
<x-page-title>
    {{ $user->name }}

    <x-slot:actions>
        <a href='{{ route( 'user.edit', $user )}}'class='btn btn-primary'>Edit</a>
    </x-slot:actions>
</x-page-title>

<p>
    User {{ $user->name }} ({{ $user->email }}) registered on {{ $user->created_at }}. Last login: {{ empty( $user->last_login ) ? 'Never' : $user->last_login }}.
</p>

<x-page-title type='h4'>
    User decks

    <x-slot:actions>
        <a href='{{ route( 'user.deck.create', $user )}}'class='btn btn-sm btn-primary'>Add deck</a>
    </x-slot:actions>
</x-page-title>

<div class='list-group'>
    @foreach ( $decks as $deck )

        <div class='list-group-item list-group-item-action d-flex w-100 align-items-center'>
            <div class='position-relative flex-grow-1'>
                <a href='{{ route( 'deck.show', $deck ) }}' class='fw-bold stretched-link'>{{ $deck->name }}</a>
            </div>

            <a href='{{ route( 'deck.edit', $deck ) }}' class='btn btn-sm btn-secondary'>Edit</a>
        </div>

    @endforeach

    {{ $decks->links() }}
</div>

@endsection