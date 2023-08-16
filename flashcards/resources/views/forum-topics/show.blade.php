@extends( 'templates.dash' )

@section( 'title', 'Forum topics' )

@section( 'content' )

<x-page-title>
    Topic '{{ $topic->title }}'


    <x-slot:actions>
        <a href='{{ route( 'forum-topic.edit', $topic ) }}' class='btn btn-primary'>Edit</a>
    </x-slot:actions>
</x-page-title>

Content


@endsection