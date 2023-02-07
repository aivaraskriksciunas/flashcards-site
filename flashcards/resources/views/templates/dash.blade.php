@extends('base')

@section('page-body')

<nav class='navbar'>
    <div class='container'>
        <a class='navbar-brand' href='{{ route( 'home' ) }}'>Flashcards</a>

        <div class='navbar-text'>
            <a href='{{ route( 'logout' ) }}'>
                {{ Auth::user()->name }}
            </a>
        </div>
    </div>
</nav>

<div class='container pt-5'>
    <div class='row'>

        <div class='col-md-2' id='_page_menu'>
            <div id='_menu_title'>
                Menu
            </div>

            @php 
                $menu_items = [
                    'Home' => [
                        'route' => 'home'
                    ],
                    'Admin users' => [
                        'route' => 'admin-user.index',
                        'group' => 'admin-user'
                    ],
                    'Registered users' => [
                        'route' => 'user.index',
                        'group' => 'user'
                    ],
                ]
            @endphp

            @foreach ( $menu_items as $key => $item )
                @php 
                    $is_active = false;
                    if ( isset( $item['group'] ) ) {
                        $is_active = request()->route()->named( "{$item['group']}*" );
                    }
                    else {
                        $is_active = request()->route()->named( "{$item['route']}" );
                    }
                @endphp

                <a 
                    href='{{ route( $item['route'] ) }}' 
                    class='_menu_item @if ( $is_active ) is-active @endif'>
                    {{ $key }}
                </a>
            @endforeach
        </div>

        <div class='col' id='_page_content'>
            @yield( 'content' )
        </div>

    </div>
</div>
    
@endsection