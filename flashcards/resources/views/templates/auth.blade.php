@extends( 'base' )

@section( 'page-body' )

<div class='auth_content-container'>
    <div class='container'>
        <div class='row'>
            <div class='auth_main-form-card col-md-4 col-sm-6 col'>

                @yield( 'content' )
            
            </div>
        </div>
    </div>
</div>

@endsection