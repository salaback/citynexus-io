<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>CityNexus Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />

    <style>
        .auth-logo {
            max-width: 90%;
        }
    </style>
    @stack('style')
    <!-- CSS Files -->
    <link href="/assets/css/main.css" rel="stylesheet">
</head>

<body class="signup-page">
<div class="wrapper">
    <div class="header header-filter" style="background-image: url('/img/background.png'); background-size: cover; background-position: top center;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                    <div class="card card-signup">
                        <div class="header auth-header text-center">
                            <img class="auth-logo" src="/img/cn_logo.png" alt="CityNexus">
                        </div>
                        @yield('main')
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="col-lg-12 text-center">
                    <div class="copyright"> &copy; {{date('Y')}} CityNexus.io, All Rights Reserved.  </div>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>

<!--  Vendor JavaScripts -->
<script src="/assets/bundles/libscripts.bundle.js"></script>

<!--  Custom JavaScripts  -->
<script src="/assets/js/main.js"></script>
<!--/ custom javascripts -->

@stack('scripts')

</html>
