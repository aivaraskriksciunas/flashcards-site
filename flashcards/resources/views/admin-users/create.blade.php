@extends( 'templates.dash' )

@section( 'title', 'Add admin user' )

@section( 'content' )
    
<x-page-title>
    New admin user 
</x-page-title>

<x-data-form :submit="route( 'admin-user.store' )">

    <x-forms.text-input name='name' label='Name' placeholder='Full name'/>

    <x-forms.text-input name='email' label='Email' type='email' placeholder='Email (used for login)'/>

    <x-forms.text-input name='password' label='Password' type='password' placeholder='Password'/>

</x-data-form>

@endsection