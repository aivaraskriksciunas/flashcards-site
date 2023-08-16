@extends( 'templates.dash' )

@section( 'title', 'Forum topics' )

@section( 'content' )

<x-page-title>
    New topic
</x-page-title>


<x-data-form :submit="route( 'forum-topic.store' )">

    <x-forms.text-input type='text' placeholder='Title' name='title' label='Forum topic title: '/>

    <x-forms.text-input type='text' placeholder='topic-title' name='slug' label='URL slug: ' />

</x-data-form>

@endsection