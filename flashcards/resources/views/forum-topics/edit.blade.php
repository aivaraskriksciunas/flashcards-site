@extends( 'templates.dash' )

@section( 'title', 'Forum topics' )

@section( 'content' )

<x-page-title>
    Editing topic '{{ $topic->title }}'
</x-page-title>


<x-data-form :submit="route( 'forum-topic.update', $topic )" method='PUT'>

    <x-forms.text-input type='text' value='{{ $topic->title }}' placeholder='Title' name='title' label='Forum topic title: '/>

    <x-forms.text-input type='text' value='{{ $topic->slug }}' disabled placeholder='topic-title' name='slug' label='URL slug: ' />

</x-data-form>

@endsection