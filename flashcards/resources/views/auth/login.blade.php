@extends ( 'templates.auth' )

@section( 'title', 'Login' )

@section( 'content' )

<form action='{{ route( 'login') }}' method='POST'>
    @csrf 

    <x-forms.text-input type='email' placeholder='email@email.com' name='email' label='Email: '/>

    <x-forms.text-input type='password' placeholder='password' name='password' label='Password: ' />

    <input class='btn btn-primary' type='submit' value='Log in'>
</form>

@endsection