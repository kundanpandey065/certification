<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sign In · Certificate Generator')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎖️</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ---- Site-wide primary button: solid orange (same as internal pages) ---- */
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

        /* ---- Action button shape used across internal pages ---- */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            height: 42px;
            padding: 0 1.15rem;
            font-size: .9rem;
            font-weight: 600;
            border-radius: .55rem;
            white-space: nowrap;
            line-height: 1;
        }

        /* ---- Page shell ---- */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background:
                radial-gradient(1100px 520px at 12% -10%, #ffe0b2 0%, rgba(255, 224, 178, 0) 60%),
                radial-gradient(900px 480px at 92% 108%, #ffd54f33 0%, rgba(255, 213, 79, 0) 60%),
                #f6f7fb;
        }

        .login-shell {
            width: 100%;
            max-width: 940px;
            background: #fff;
            border-radius: 1.1rem;
            overflow: hidden;
            box-shadow: 0 18px 48px rgba(20, 30, 60, .12);
        }

        /* ---- Left brand panel ---- */
        .login-brand {
            position: relative;
            padding: 2.75rem 2.5rem;
            color: #fff;
            background: linear-gradient(160deg, #f57c00 0%, #ef6c00 55%, #b34b00 100%);
            overflow: hidden;
        }

        .login-brand::after {
            content: "";
            position: absolute;
            right: -90px;
            bottom: -110px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .10);
        }

        .login-brand::before {
            content: "";
            position: absolute;
            left: -70px;
            top: -80px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .login-brand>* {
            position: relative;
            z-index: 1;
        }

        .login-brand .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(145deg, #ffd54f, #f57c00);
            box-shadow:
                inset 0 2px 3px rgba(255, 255, 255, .6),
                inset 0 -3px 4px rgba(0, 0, 0, .25),
                0 3px 8px rgba(0, 0, 0, .3);
            font-size: 26px;
            line-height: 1;
        }

        .login-brand .brand-icon i {
            color: #fff8e1;
            text-shadow: 0 1px 1px rgba(0, 0, 0, .4);
        }

        .login-brand h1 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: .3px;
            margin: 1.1rem 0 .4rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, .2);
        }

        .login-brand .brand-tagline {
            font-size: .95rem;
            color: #fff3e0;
            margin-bottom: 1.75rem;
            line-height: 1.55;
        }

        .login-brand .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 1.75rem;
        }

        .login-brand .feature-list li {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-bottom: 1rem;
            font-size: .9rem;
            line-height: 1.45;
        }

        .login-brand .feature-list li:last-child {
            margin-bottom: 0;
        }

        .login-brand .feature-list .fa-solid {
            flex: 0 0 30px;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .55rem;
            background: rgba(255, 255, 255, .18);
            font-size: .85rem;
        }

        .login-brand .feature-list strong {
            display: block;
            font-weight: 700;
        }

        .login-brand .feature-list span {
            color: #ffe6c7;
            font-size: .82rem;
        }

        .login-brand .brand-foot {
            font-size: .78rem;
            color: #ffe0b2;
            border-top: 1px solid rgba(255, 255, 255, .22);
            padding-top: 1rem;
        }

        /* ---- Right form panel ---- */
        .login-form-panel {
            padding: 2.75rem 2.5rem;
        }

        .login-form-panel .eyebrow {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #f57c00;
            margin-bottom: .45rem;
        }

        .login-form-panel h2 {
            font-size: 1.55rem;
            font-weight: 700;
            color: #1b2a4a;
            margin-bottom: .4rem;
        }

        .login-form-panel .lead-text {
            font-size: .92rem;
            color: #6b7280;
            margin-bottom: 1.75rem;
            line-height: 1.55;
        }

        .login-form-panel .form-label {
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6b7280;
            margin-bottom: .35rem;
        }

        .login-form-panel .input-group-text {
            background: #f6f7fb;
            border-right: 0;
            color: #9aa5b6;
        }

        .login-form-panel .form-control {
            border-left: 0;
            padding-top: .6rem;
            padding-bottom: .6rem;
        }

        .login-form-panel .input-group .form-control,
        .login-form-panel .input-group .input-group-text,
        .login-form-panel .input-group .btn {
            border-color: #dfe3ec;
        }

        .login-form-panel .input-group:focus-within .input-group-text,
        .login-form-panel .input-group:focus-within .form-control,
        .login-form-panel .input-group:focus-within .btn-toggle-pw {
            border-color: #f57c00;
        }

        .login-form-panel .form-control:focus {
            box-shadow: none;
        }

        .login-form-panel .btn-toggle-pw {
            background: #f6f7fb;
            border-left: 0;
            color: #9aa5b6;
        }

        .login-form-panel .btn-toggle-pw:hover {
            color: #f57c00;
        }

        .login-form-panel .form-check-input:checked {
            background-color: #f57c00;
            border-color: #f57c00;
        }

        .login-form-panel .form-check-input:focus {
            border-color: #f57c00;
            box-shadow: 0 0 0 .2rem rgba(245, 124, 0, .2);
        }

        .login-form-panel .helper-note {
            font-size: .8rem;
            color: #9aa5b6;
        }

        .login-form-panel .panel-divider {
            border-top: 1px dashed #e3e8f0;
            margin: 1.5rem 0 1.1rem;
        }

        /* Invalid feedback needs to show while inputs sit inside an input-group */
        .login-form-panel .input-group~.invalid-feedback,
        .login-form-panel .invalid-feedback.d-block {
            display: block;
        }

        @media (max-width: 991.98px) {
            .login-brand {
                padding: 2rem 1.75rem;
            }

            .login-form-panel {
                padding: 2rem 1.75rem;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="login-page">
        <div class="login-shell">
            <div class="row g-0">

                {{-- ---------- Brand / info panel ---------- --}}
                <div class="col-lg-5 login-brand d-flex flex-column">
                    <span class="brand-icon">
                        <i class="fa-solid fa-award"></i>
                    </span>

                    <h1>Certificate Generator</h1>
                    <p class="brand-tagline">
                        The central console for issuing, tracking and exporting skill
                        certificates across every district, school and job role.
                    </p>

                    <ul class="feature-list">
                        <li>
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>
                                <strong>Live dashboard</strong>
                                Certificates, districts, schools and NSQF levels at a glance.
                            </span>
                        </li>
                        <li>
                            <i class="fa-solid fa-table-list"></i>
                            <span>
                                <strong>Searchable records</strong>
                                Filter by district, school, standard or job role in seconds.
                            </span>
                        </li>
                        <li>
                            <i class="fa-solid fa-file-arrow-down"></i>
                            <span>
                                <strong>Bulk PDF exports</strong>
                                Generate thousands of certificates in a single download.
                            </span>
                        </li>
                    </ul>

                    <div class="brand-foot mt-auto">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        Authorised access only &middot; &copy; {{ date('Y') }} Certificate Generator
                    </div>
                </div>

                {{-- ---------- Login form panel ---------- --}}
                <div class="col-lg-7 login-form-panel">
                    <div class="eyebrow">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Secure Sign In
                    </div>
                    <h2>Welcome back</h2>
                    <p class="lead-text">
                        Sign in with your administrator account to manage certificate records,
                        review the dashboard and run bulk exports.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success py-2 px-3 small" role="alert">
                            <i class="fa-solid fa-circle-check me-1"></i> {{ session('status') }}
                        </div>
                    @endif

                    @error('email')
                        <div class="alert alert-danger py-2 px-3 small" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                        </div>
                    @enderror

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="you@example.com" autocomplete="username"
                                    required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Enter your password" autocomplete="current-password" required>
                                <button class="btn btn-toggle-pw" type="button" id="togglePassword"
                                    aria-label="Show password">
                                    <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="remember">
                                    Keep me signed in on this device
                                </label>
                            </div>
                            <span class="helper-note">
                                <i class="fa-solid fa-circle-info me-1"></i> Forgot password? Contact the administrator.
                            </span>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-action btn-primary" id="loginBtn">
                                <i class="fa-solid fa-right-to-bracket"></i> Sign In to Dashboard
                            </button>
                        </div>
                    </form>

                    <div class="panel-divider"></div>

                    <p class="helper-note mb-0">
                        <i class="fa-solid fa-lock me-1"></i>
                        This is a restricted system. All sign-in attempts and certificate
                        downloads are recorded against your account.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <script>
        (function () {
            // Show / hide password
            const toggle = document.getElementById('togglePassword');
            const field = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            toggle.addEventListener('click', function () {
                const hidden = field.type === 'password';
                field.type = hidden ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !hidden);
                icon.classList.toggle('fa-eye-slash', hidden);
                toggle.setAttribute('aria-label', hidden ? 'Hide password' : 'Show password');
            });

            // Disable the button while signing in to avoid double submits
            const form = document.querySelector('form');
            const btn = document.getElementById('loginBtn');

            form.addEventListener('submit', function () {
                if (!form.checkValidity()) return;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Signing in…';
            });
        })();
    </script>
    @yield('scripts')
</body>

</html>
