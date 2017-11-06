<!DOCTYPE html>
<html>
    <head>

        <!-- Metadata -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">


        <!-- CSS -->
        <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">


        <!-- JS -->
        <script src="{{ asset('/assets/js/all.js') }}" language="javascript"></script>

        <title>FivMoon</title>


    </head>
    <body>

        <div id="wrapper">
                <nav class="navbar-default navbar-static-side" role="navigation">
                    @include('layouts.driver.sidebar')
                </nav>



        <div id="page-wrapper" class="gray-bg">
                @include('layouts.default.topbar')

                @yield('content')

                @include('layouts.default.footer')

            </div>

        </div>

    </body>
</html>
