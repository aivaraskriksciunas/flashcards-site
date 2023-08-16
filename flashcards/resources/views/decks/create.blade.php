@extends( 'templates.dash' )

@section( 'title', 'Add deck' )

@section( 'content' )
    
<x-page-title>
    Creating deck
</x-page-title>

<p>Adding deck for user <a href='{{ route( 'user.show', $user ) }}'>{{ $user->name }}</a> ({{ $user->email }}).</p>

<x-data-form :submit="route( 'user.deck.store', $user )">

    <x-forms.text-input name='name' label='Name' placeholder='Deck title'/>

</x-data-form>

@endsection