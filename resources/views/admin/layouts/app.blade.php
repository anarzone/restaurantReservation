<!DOCTYPE html>
<html dir="ltr" lang="en">
@include('admin.layouts.partials.header')
<body>
  <style>
  .loader{
    position:absolute;
    left:0;
    right:0;
    top:0;
    bottom:0;
    margin:auto;
    display: none;
  }
  </style>
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="mini-sidebar"
  data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
  @include('admin.layouts.nav.topbar')
  @include('admin.layouts.nav.sidebar')
  <!-- ============================================================== -->
  <!-- Page wrapper  -->
  <!-- ============================================================== -->
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    @if (trim($__env->yieldContent('page-title')))
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-7 align-self-center">
          <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">@yield('page-title')</h4>
        </div>
        <div class="col-5 align-self-center">
          <div class="customize-input float-right">

          </div>
        </div>
      </div>
    </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
      @yield('content')
      <img src="{{asset('images/loader.gif')}}" alt="loader" class="loader">
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    @include('admin.layouts.partials.footer')
  </div>
</div>
<script src="{{asset('back/assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('back/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('back/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- apps -->
<!-- apps -->
<script src="{{asset('back/dist/js/app-style-switcher.js')}}"></script>
<script src="{{asset('back/dist/js/feather.min.js')}}"></script>
<script src="{{asset('back/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{asset('back/dist/js/sidebarmenu.js')}}"></script>

<!-- global libraries -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!--Custom JavaScript -->
<script src="{{asset('back/dist/js/custom.min.js')}}"></script>
<script src="{{asset('back/dist/js/dry_functions.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@yield('js')
</body>
</html>
