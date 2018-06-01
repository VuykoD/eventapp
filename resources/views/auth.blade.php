<!DOCTYPE html>
<html lang="en" data-textdirection="LTR" class="loading">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>@yield('title', 'Login')</title>
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/ico/apple-icon-60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/ico/apple-icon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/ico/apple-icon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/ico/apple-icon-152.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/ico/favicon.ico">
    <link rel="shortcut icon" type="image/png" href="/assets/ico/favicon-32.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN VENDOR CSS-->
    <!-- build:css robust-assets/css/vendors.min.css-->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/plugins/extensions/toastr.css">
    <!-- /build-->
    <!-- BEGIN VENDOR CSS-->
    <!-- BEGIN Font icons-->
    <link rel="stylesheet" type="text/css" href="/assets/fonts/icomoon.css">
    <link rel="stylesheet" type="text/css" href="/assets/fonts/flag-icon-css/css/flag-icon.min.css">
    <!-- END Font icons-->
    <!-- BEGIN Plugins CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/plugins/sliders/slick/slick.css">
    <!-- END Plugins CSS-->

    <!-- BEGIN Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/plugins/forms/icheck/custom.css">
    <!-- END Vendor CSS-->
    <!-- BEGIN ROBUST CSS-->
    <!-- build:css robust-assets/css/app.min.css-->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-robust.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/colors.css">
    <!-- /build-->
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    @yield('css')
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <!-- END Custom CSS-->
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

  </head>
  <body data-open="click" data-menu="vertical-content-menu" data-col="1-column" class="vertical-layout vertical-content-menu 1-column  blank-page blank-page">
    <!-- START PRELOADER-->

    <div id="preloader-wrapper">
      <div id="loader">
        <div class="line-scale loader-white">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
      <div class="loader-section section-top bg-blue-grey"></div>
      <div class="loader-section section-bottom bg-blue-grey"></div>
    </div>

    <!-- END PRELOADER-->
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="robust-content content container-fluid">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
<section class="flexbox-container">
    
    <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1 box-shadow-2 p-0">

        @include('partials.messages')

        <div class="card border-grey border-lighten-3 m-0">
            <div class="card-header no-border">
                <div class="card-title text-xs-center">
                    <div class="p-1"><img src="/assets/images/logo/robust-logo-dark.png" alt="branding logo"></div>
                </div>
                <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>
                    @yield('sub_title', 'Please Login')
                </span></h6>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">  

                    @yield('content')
                    
                </div>
            </div>

            {{-- <div class="card-footer">
                <div class="">
                    <p class="float-sm-left text-xs-center m-0"><a href="recover-password.html" class="card-link">Recover password</a></p>
                    <p class="float-sm-right text-xs-center m-0">New to Robust? <a href="register-simple.html" class="card-link">Sign Up</a></p>
                </div>
            </div> --}}
        </div>
    </div>

    @yield('vendor_login')

</section>

        </div>
      </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!-- BEGIN VENDOR JS-->
    <!-- build:js robust-assets/js/vendors.min.js-->
    <script src="/assets/js/core/libraries/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/tether.min.js" type="text/javascript"></script>
    <script src="/assets/js/core/libraries/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/unison.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/blockUI.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/jquery.matchHeight-min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/jquery-sliding-menu.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/sliders/slick/slick.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/screenfull.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/extensions/toastr.min.js" type="text/javascript"></script>
    <!-- /build-->
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/assets/js/plugins/ui/headroom.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/forms/validation/jqBootstrapValidation.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <!-- build:js robust-assets/js/app.min.js-->
    <script src="/assets/js/core/robust-menu.js" type="text/javascript"></script>
    <script src="/assets/js/core/robust.js" type="text/javascript"></script>
    <script src="/assets/js/components/ui/fullscreenSearch.js" type="text/javascript"></script>
    <!-- /build-->
    <!-- END ROBUST JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="/assets/js/components/forms/form-login-register.js" type="text/javascript"></script>
    <script src="/assets/js/components/forms/form-vendor-register.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
    @yield('javascript')

    @include('partials.exception-toastr')
    
  </body>
</html>
