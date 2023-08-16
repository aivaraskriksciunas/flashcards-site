@extends( 'templates.dash' )

@section( 'title', 'Edit deck' )

@section( 'content' )
    
<x-page-title>
    Editing deck
</x-page-title>

<x-data-form 
    :submit="route( 'deck.update', $deck )"
    :delete="route( 'deck.destroy', $deck )"
    method='PUT'>

    <x-forms.text-input :value="$deck->name" name='name' label='Name' placeholder='Deck title'/>

</x-data-form>

@endsection