<!DOCTYPE html>
<html lang="en" data-bs-theme='dark'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset( 'main.css' ) }}">

    <script src='{{ asset( 'main.bundle.js' ) }}'></script>

    <title>@yield( 'title' )</title>
</head>
<body>
    
    @yield( 'page-body' )

</body>
</html>