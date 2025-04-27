<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" sizes="32x32" href="/ico/favicon.ico">
{{--    <link rel="icon" type="image/png" sizes="16x16" href="/ico/favicon-16x16.png">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', '') }}</title>

    {{-- Laravel Mix - CSS File --}}
    <link rel="stylesheet" href="{{ mix('css/metis.css') }}">
    <link
        href="https://cdn.datatables.net/v/bs/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sr-1.3.0/datatables.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/lib/select2/select2-bootstrap.css">
    @yield('header')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
</head>
<body>
<div class="bg-dark dk" id="wrap">
    @if(auth()->check())
        @include('metis::layouts.nav')
    @endif
    @yield('content')
</div>
<!-- /#wrap -->
<footer class="Footer dker">
    <p>{{ now()->format('Y') }} &copy; {{ config('app.name', 'Newroz') }}</p>
</footer>
<!-- /#footer -->
<x-metis::modal.default></x-metis::modal.default>

{{-- Laravel Mix - JS File --}}
<script src="{{ mix('js/metis.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs/jszip-3.10.1/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sr-1.3.0/datatables.min.js"></script>
<script type="text/javascript" src="{{ asset('js/sweetalert.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('plugins/safeauth/js/safeAuth.js') }}"></script>
<script>
    let modal = document.getElementById('myModal');
    let reload = $('#reload');
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    @if(Session::has('message'))
    Toast.fire({
        icon: '{{ Session::get('message.label')}}',
        title: '{{ Session::get('message.content')}}'
    });
    @endif

    @if(session()->has('status') && session()->has('message'))
    Toast.fire({
        icon: '{{ session()->get('status') ? 'success' : 'error' }}',
        title: '{{ session()->get('message') }}'
    })
    @endif

    safeAuth({
        logoutUrl: "/dashboard/auth/logout",
        loginUrl: "/auth/login",
        inactiveTime: 25 * 60 * 1000,
        warningTime: 60 * 1000,
        countdownTime: 59,
        showToast: function (message, type) {
            Toast.fire({
                icon: type,
                title: message
            })
        }
    });

    function defaultToast(status = true, message = null) {
        Toast.fire({
            icon: status ? 'success' : 'error',
            title: message ?? (status ? "Your request successfully processed" : 'Your request failed to process')
        })
    }

    jQuery(function ($) {
        $(document).ajaxStop(function (e) {
            $(reload).hide();
        });
        // $(document).ajaxSend(function (evt, request, settings) {
        //     $(reload).show();
        // })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.select2').select2();

        $('#modalForm').submit(function (e) {
            let url = $(this).attr('action');
            let data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function (response, textStatus, xhr) {
                    // response = JSON.parse( response );
                    if (response.status === true) {
                        $(modal).modal('hide');
                    }
                    Toast.fire({
                        icon: response.status ? 'success' : 'error',
                        title: response.message
                    });
                }
            })
            return false;
        });
        $(document).on('click', '.toolbar .dropdown-menu', function (e) {
            e.stopPropagation();
        });
    });
</script>
@yield('footer')
</body>
</html>
