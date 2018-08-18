<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ env('APP_NAME') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="https://www.rbg.vic.gov.au/common/img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="https://www.rbg.vic.gov.au/common/fonts/451576/645A29A9775E15EA2.css" />
        <link rel="stylesheet" type="text/css" href="{{ env('APP_URL') }}/css/font-awesome.css" />
        <link rel="stylesheet" type="text/css" href="{{ env('APP_URL') }}/css/app.css" />
        <script type="text/javascript">
            @isset($data)
            window.hortflora_server_data = "{!! addslashes(json_encode($data)) !!}";
            @endisset
        </script>
    </head>
    <body class="hortflora">
        <div id="app"></div> <!-- /#app -->
        <script src="{{ env('APP_URL') }}/js/app.js"></script>
    </body>
</html>
