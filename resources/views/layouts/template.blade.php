<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('design/dark/assets/images/LogoTifico.png') }}">
    <title>SF Oil Management</title>
    <!--CSS -->
    @include('layouts.style')
</head>

<body class="horizontal dark  ">
    <div class="wrapper">
        @include('layouts.navbar')
        <main role="main" class="main-content">
            <div class="container-fluid">
                @yield('content')
            </div> <!-- .container-fluid -->
        </main> <!-- main -->
    </div> <!-- .wrapper -->
    @include('layouts.script')
    @stack('scripts')
</body>

</html>
