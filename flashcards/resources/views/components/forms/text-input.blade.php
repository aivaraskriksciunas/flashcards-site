<div class='form-group mb-4'>

    <label class='mb-1'>
        @empty( $label ) 
            {{ $slot }}
        @else 
            {{ $label }}
        @endempty
    </label>

    <input 
        {{ $attributes->class([ 
            'form-control',
            'is-invalid' => $errors->has( $name ),
            'is-valid'   => !$errors->has( $name ) && !empty( old( $name ) ),
        ]) }}
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ empty( $value ) ? old( $name ) : $value }}"
        placeholder="{{ $placeholder }}"
    />
    @error( $name )
        @foreach ( $errors->get( $name ) as $message )  
            <div class='invalid-feedback'>{{ $message }}</div>
        @endforeach
    @enderror

</div>