@extends( 'templates.dash' )

@section( 'title', 'Admin users' )

@section( 'content' )

<x-page-title>
    Admin Users 

    <x-slot:actions>
        <a href='{{ route( 'admin-user.create' )}}'class='btn btn-primary'>New</a>
    </x-slot:actions>
</x-page-title>

<div class='list-group'>
    @foreach ( $users as $user )

        <a href='{{ route( 'user.show', $user->id ) }}' class='list-group-item list-group-item-action'>
            <div class='fw-bold'>{{ $user->name }}</div>
            {{ $user->email }}
        </a>

    @endforeach
</div>

@endsection 