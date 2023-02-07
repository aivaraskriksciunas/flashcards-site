@props([ 'type' => 'h1' ])

<div class='page-title d-flex align-items-center @if( $type == 'h1' ) mb-4 @else mb-2 @endif'>
    <div class='flex-grow-1'>
        <{{ $type }} class='mb-0'>{{ $slot }}</{{ $type }}>
    </div>

    @isset( $actions )
        <div class='py-2'>
            {{ $actions }}
        </div>
    @endisset

</div>