
@isset ( $delete )
    <form action='{{ $delete }}' method='POST' class='_delete_object_form'>
        @csrf 
        @method( 'DELETE' )
    </form>
@endisset

<form action='{{ $submit }}' method='{{ $method === 'GET' ? 'GET' : 'POST' }}'>

    @csrf
    @if ( $method !== 'GET' )
        @method( $method )
    @endif

    {{ $slot }}

    <div class='d-flex'>
        <div class='flex-grow-1'>
            <input type='submit' class='btn btn-success' value='Save'>

            @if ( $showCancel )
                <a href='{{ url()->previous() }}' class='btn btn-secondary'>Cancel</a>
            @endif
        </div>

        @isset( $delete )
            <div class='_delete_object_button btn btn-danger'>Delete</div>
        @endisset

    </div>
</form>