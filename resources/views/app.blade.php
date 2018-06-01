<!DOCTYPE html>
<html lang="en" data-textdirection="LTR" class="loading">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>@yield('title', app_name())</title>
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
    <!-- BEGIN Vendor CSS-->
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
  <body data-open="click" data-menu="vertical-content-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns ">
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

    <!-- navbar-fixed-top-->
    @include('partials.nav')

    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="robust-content content container-fluid">
      <div class="content-wrapper">
        <div class="content-header row">
          <div class="content-header-left col-md-6 col-xs-12 mb-1">

            @yield('page-header', app_name())

          </div>
          <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
            <div class="breadcrumb-wrapper col-xs-12">

                {{-- *** BREADCRUMBS *** --}}

              {{-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Starter Kit</a>
                </li>
                <li class="breadcrumb-item active">Static Layout
                </li>
              </ol> --}}
            </div>
          </div>
        </div>

    @if(access()->user() !== null)
       @include('partials.usermenu')
    @endif

        <div class="content-body">

            @if ($errors->any())
            <section class="row">
                <div class="col-sm-12">
                    <div class="card">

                        @include('partials.messages')

                    </div>
                </div>
            </section>
            @endif
                   
            @yield('content')


        </div>


      </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    {{-- @include('partials.footer') --}}

    <!-- BEGIN VENDOR JS-->
    <!-- build:js robust-assets/js/vendors.min.js-->
    <script src="/assets/js/core/libraries/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/tether.min.js" type="text/javascript"></script>
    <script src="/assets/js/core/libraries/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/unison.min.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/ui/jquery-sliding-menu.js" type="text/javascript"></script>
    <script src="/assets/js/plugins/extensions/toastr.min.js" type="text/javascript"></script>
    <!-- /build-->
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/assets/js/plugins/ui/headroom.min.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <!-- build:js robust-assets/js/app.min.js-->
    <script src="/assets/js/core/robust-menu.js" type="text/javascript"></script>
    <script src="/assets/js/core/robust.js" type="text/javascript"></script>
    <!-- /build-->
    <!-- END ROBUST JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    @yield('javascript')
    <!-- END PAGE LEVEL JS-->

    @include('partials.exception-toastr')
    @include('partials.messages-flash')
    
  </body>
</html>
