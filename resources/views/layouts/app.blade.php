<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSetting['name'] }}</title>

    <!-- Fonts -->
    <link href="{{url('css/loginstyle.css')}}"rel="stylesheet">
    <link rel="stylesheet" href="{{url('vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('vendor/css/pages/page-auth.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

<body class="body">
        <main class="py-4">
             @yield('content')
        </main>
    </div>
</body>
</html>


