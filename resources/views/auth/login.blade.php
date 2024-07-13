<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('/assets/template/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/template/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/template/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/template/vendors/typicons/typicons.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/template/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/template/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('/assets/template/css/vertical-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('/assets/img/logo_unila.png')}}" />
</head>

<body>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="w-100 mx-0">
                    <div class="col-lg-4 mx-auto"">
                        <div class=" auth-form-light text-left py-5 px-4 px-sm-5">
                        <div class="brand-logo">
                            <img src="{{ asset('/assets/img/logo_unila.png')}}" alt="logo">
                        </div>
                        <h4>Selamat Datang!</h4>
                        <h6 class="fw-light">Silahkan login.</h6>
                        <form method="POST" action="{{ route('login') }} " class="pt-3">
                            @csrf
                            <!-- Email Address -->
                            <div class="form-group">
                                <input id="email" class="form-control form-control-lg" placeholder="Email" type="email" name="email" :value="old('email')" autocomplete="username" required autofocus />
                            </div>
                            <!-- Password -->
                            <div class="form-group">
                                <input id="password" class="form-control form-control-lg" placeholder="Password" type="password" name="password" required autocomplete="current-password" />
                            </div>
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />
                            <!-- Submit -->
                            <div class="mt-3">
                                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                    {{ __('Log in') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/template/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('/assets/template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('/assets/template/js/off-canvas.js')}}"></script>
    <script src="{{ asset('/assets/template/js/settings.js')}}"></script>
    <script src="{{ asset('/assets/template/js/todolist.js')}}"></script>
    <!-- endinject -->
</body>