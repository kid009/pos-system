<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('admin_assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('admin_assets/images/favicon.png') }}" type="image/x-icon">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/themify.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/feather-icon.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('admin_assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/responsive.css') }}">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <section>
        <div class="p-0 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="login-card">
                        <form method="POST" action="{{ route('login') }}" class="theme-form login-form">
                            @csrf
                            
                            <h4>Login</h4>
                            <h6>Welcome back! Log in to your account.</h6>
                            <div class="form-group">
                                <label>Email Address</label>
                                <div class="input-group"><span class="input-group-text">
                                    <i class="icon-email"></i></span>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email">
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" >
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page-wrapper end-->
    <!-- latest jquery-->
    <script src="{{ asset('admin_assets/js/jquery-3.5.1.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('admin_assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('admin_assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('admin_assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Theme js-->
    <script src="{{ asset('admin_assets/js/script.js') }}"></script>
    <script src="{{ asset('admin_assets/js/theme-customizer/customizer.js') }}"></script>
    <!-- login js-->
    <!-- Plugin used-->
</body>

</html>