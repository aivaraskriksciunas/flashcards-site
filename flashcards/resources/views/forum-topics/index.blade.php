@extends( 'templates.dash' )

@section( 'title', 'Forum topics' )

@section( 'content' )

<x-page-title>
    Forum topics 

    <x-slot:actions>
        <a href='{{ route( 'forum-topic.create' )}}'class='btn btn-primary'>New</a>
    </x-slot:actions>
</x-page-title>

<div class='list-group'>
    @foreach ( $topics as $topic )

        <a href='{{ route( 'forum-topic.show', $topic ) }}' class='list-group-item list-group-item-action'>
            {{ $topic->title }}
        </a>

    @endforeach
</div>

@endsection