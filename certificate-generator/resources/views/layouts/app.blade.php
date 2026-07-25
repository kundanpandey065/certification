<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Certificate Generator')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎖️</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Site-wide primary button: solid orange */
        .btn-primary {
            background: #f57c00;
            border-color: #f57c00;
            color: #fff;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background: #ef6c00 !important;
            border-color: #ef6c00 !important;
            color: #fff !important;
        }

        .btn-primary:disabled,
        .btn-primary.disabled {
            background: #ffcc80;
            border-color: #ffcc80;
        }

        .btn-check:checked+.btn-primary,
        .btn-primary:not(:disabled):not(.disabled).active {
            background: #ef6c00;
            border-color: #ef6c00;
        }

        /* Header text: black */
        .navbar,
        .navbar .navbar-brand,
        .navbar .nav-link,
        .navbar .dropdown-item {
            color: #000 !important;
        }

        .navbar .nav-link.active {
            font-weight: bold;
        }
    </style>
    @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">

    @include('layouts.header')

    <main class="flex-fill py-4">
        @yield('content')
    </main>\

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>

</html>
