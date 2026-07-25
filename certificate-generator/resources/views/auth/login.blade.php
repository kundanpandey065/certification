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
    @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">
<div class="container" style="max-width:400px;margin-top:3rem;">
  <h4 class="mb-3 text-center">Login</h4>
  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input
        id="email"
        type="email"
        class="form-control @error('email') is-invalid @enderror"
        name="email"
        value="{{ old('email') }}"
        required autofocus
      >
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input
        id="password"
        type="password"
        class="form-control @error('password') is-invalid @enderror"
        name="password"
        required
      >
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-check mb-3">
      <input
        class="form-check-input"
        type="checkbox"
        name="remember"
        id="remember"
        {{ old('remember') ? 'checked' : '' }}
      >
      <label class="form-check-label" for="remember">
        Remember Me
      </label>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-primary">
        Login
      </button>
    </div>
  </form>
</div>
</body>

</html>
