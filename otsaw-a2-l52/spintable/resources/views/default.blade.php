<!DOCTYPE html>
<html>
    <head>

        <!-- Metadata -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">


        <!-- CSS -->
        <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('/assets/css/all.css') }}" rel="stylesheet">


        <!-- JS -->
        <script src="{{ asset('/assets/js/all.js') }}" language="javascript"></script>

        <!-- FAVICON -->
        <link rel="icon" type="image/png" href="{{ asset('/assets/img/fiv/favicon.png') }}" />

        <title>Quabii</title>


    </head>
    <body>

        <div id="wrapper">
                <nav class="navbar-default navbar-static-side" role="navigation">
                    @include('layouts.default.sidebar')
                </nav>



        <div id="page-wrapper" class="gray-bg">
                @include('layouts.default.topbar')

                @yield('content')

                @include('layouts.default.footer')

            </div>

        </div>

    </body>
</html>
