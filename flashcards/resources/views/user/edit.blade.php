@extends( 'templates.dash' )

@section( 'title', 'Edit user' )

@section( 'content' )
    
<x-page-title>
    Editing user
</x-page-title>

<x-data-form 
    :submit="route( 'user.update', $user )"
    :delete="route( 'user.destroy', $user )"
    method='PUT'>

    <x-forms.text-input :value="$user->name" name='name' label='Name' placeholder='Full name'/>

    <x-forms.text-input :value="$user->email" name='email' label='Email' type='email' placeholder='Email (used for login)'/>

    <x-forms.text-input name='password' label='Password' type='password' placeholder='Password'/>

</x-data-form>

@endsection