<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>


    <link rel="icon" href="{{ asset('/') }}fav/cropped-FAV-32x32.png" sizes="32x32"/>
    <link rel="icon" href="{{ asset('/') }}fav/cropped-FAV-192x192.png" sizes="192x192"/>
    <link rel="apple-touch-icon" href="{{ asset('/') }}fav/cropped-FAV-180x180.png"/>

    {{--      <link href="{{ mix('/css/app.css') }}" rel="stylesheet" crossorigin="anonymous"/>--}}
    {{--      <script src="{{ mix('/js/app.js') }}" defer crossorigin="anonymous"></script>--}}
    {{--    <script src="{{ asset("/js/vendors.min.js?v=".time()) }}" crossorigin="anonymous"></script>--}}
    {{--    <script src="{{ asset('/js/json.js') }}" crossorigin="anonymous"></script>--}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body>
<div id="app" class="h-100" data-page="{{ json_encode($page) }}"></div>
</body>
</html>
