<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{asset('favicon.ico')}}" rel="icon">
    <title>აჭარის არქივი</title>
    <link rel="stylesheet" href="{{asset('css/viewer.css')}}">
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('fonts/stylesheet.css')}} ">

    @yield('css')

    @FilemanagerScript
</head>

<body class="hold-transition layout-top-nav">
    <div class="container-fluid">
        @yield('content')
    </div>
    <script src="{{asset('js/viewer.js')}}"></script>
    @yield('js')
</body>

</html>
