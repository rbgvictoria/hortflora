<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ env('APP_NAME') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="https://www.rbg.vic.gov.au/common/img/favicon.ico">
        @section('stylesheets')
        <link rel="stylesheet" type="text/css" href="https://www.rbg.vic.gov.au/common/fonts/451576/645A29A9775E15EA2.css" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ env('APP_URL') }}/css/jqueryui.autocomplete.css" />
        <link rel="stylesheet" type="text/css" href="{{ env('APP_URL') . mix('/css/app.css') }}" />
        @show
        @section('scripts')
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript" src="{{ env('APP_URL') }}/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        @show
    </head>
    <body class="hortflora">
        <div id="app">
            <header id="banner">
                @include('partials.banner')
            </header>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        @include('partials.flash-message')
                    </div>
                </div>
            </div>

            @yield('content')

            <footer class="footer clearfix">
                @include('partials.footer')
            </footer>
        </div> <!-- /#app -->
        @section('footer-scripts')
        <script src="{{ env('APP_URL') . mix('/js/app.js') }}"></script>
        @show
    </body>
</html>
