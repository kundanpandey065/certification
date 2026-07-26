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

        /* Brand icon + 3D heading */
        .navbar-brand .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(145deg, #ffd54f, #f57c00);
            box-shadow:
                inset 0 2px 3px rgba(255, 255, 255, 0.6),
                inset 0 -3px 4px rgba(0, 0, 0, 0.25),
                0 3px 6px rgba(0, 0, 0, 0.3);
            font-size: 22px;
            line-height: 1;
        }

        .navbar-brand .brand-icon i {
            color: #fff8e1;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand .brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #8a4b00 !important;
            background: linear-gradient(180deg, #fff3e0 0%, #ffcc80 45%, #f57c00 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow:
                0 1px 0 #fff8e1,
                0 2px 0 #ffe0b2,
                0 3px 0 #ffcc80,
                0 4px 3px rgba(0, 0, 0, 0.35);
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
