<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{--    <link rel="icon" type="image/png" sizes="32x32" href="/ico/favicon-32x32.png">--}}
    <link rel="icon" type="image/png" sizes="32x32" href="/ico/favicon.ico">
    <title>{{ $title ?? 'login -Kartat' }}</title>

    {{-- Laravel Mix - CSS File --}}
    <link rel="stylesheet" href="{{ mix('css/metis.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    @yield('header')
</head>
<body class="login" style="margin-top: 200px">
    @yield('content')
{{-- Laravel Mix - JS File --}}
<script src="{{ mix('js/metis.js') }}"></script>
@yield('footer')
</body>
</html>
