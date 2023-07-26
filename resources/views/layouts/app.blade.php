<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('constants.APP_NAME') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <!-- TOAST library -->
    <link rel="stylesheet" href="{{ asset('assets/libraries/toast/css/simple-notify.min.css') }}" />

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
      input.error {
          border-color:red;
          box-shadow:0 0 0 2px tomato
      }
      select.error {
          border-color:red;
          box-shadow:0 0 0 2px tomato
      }
       .error{
        color: tomato !important;
        font-weight: bold;
       }
       textarea.error{
        border-color:red;
          box-shadow:0 0 0 2px tomato
       }
       .cursor-pointer{
        cursor: pointer;
       }

    </style>
    @yield('styles')
</head>

<body>

    <!-- ======= Header ======= -->
      @include('includes.header')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
     @include('includes.sidebar')
    <!-- End Sidebar-->

    <main id="main" class="main">

         @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('includes.footer')
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- TOAST library -->
   <script src="{{ asset('assets/libraries/toast/js/simple-notify.min.js')}}"></script>
   @include('includes.messages')
    @yield('scripts')

</body>

</html>
