<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>:: Oakleaf - Admin ::</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />

    <!-- CSS Files -->
    <link href="/assets/css/main.css" rel="stylesheet">
</head>

<body class="signup-page">
<div class="wrapper">
    <div class="header header-filter" style="background-image: url('/assets/images/login-bg.jpg'); background-size: cover; background-position: top center;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                    <div class="card card-signup">
                        @yield('main')
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="col-lg-12 text-center">
                    <div class="copyright"> &copy; {{date('Y')}} Alaback Strategies, Inc., All Rights Reserved.  </div>
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
</html>
