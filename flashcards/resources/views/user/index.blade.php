@extends( 'templates.dash' )

@section( 'title', 'Registered users' )

@section( 'content' )

<x-page-title>
    Registered Users 

    <x-slot:actions>
        <a href='{{ route( 'user.create' )}}'class='btn btn-primary'>New</a>
    </x-slot:actions>
</x-page-title>

<div class='filters mb-2'>
    <form action='' method='get' class='d-flex'>
        <div class='form-group'>
            <input type='text' name='search' placeholder='search' class='form-control'>
        </div>
        <div class='flex-grow-1'></div>
        <input type='submit' value='Search' class='btn btn-primary btn-sm'>
    </form>
</div>

<div class='list-group mb-3'>
    @foreach ( $users as $user )

        <div class='list-group-item list-group-item-action d-flex w-100 align-items-center'>
            <div class='position-relative flex-grow-1'>
                <div>
                    <a href='{{ route( 'user.show', $user->id ) }}' class='fw-bold stretched-link'>{{ $user->name }}</a>
                </div>
                {{ $user->email }}
            </div>

            <a href='{{ route( 'user.edit', $user->id ) }}' class='btn btn-sm btn-secondary'>Edit</a>
        </div>

    @endforeach
    
</div>

{{ $users->links() }}

@endsection 