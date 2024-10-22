<!DOCTYPE html>
<html class="layout-menu-fixed">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title)?$title.' - '.trans('panel.site_title'):trans('panel.site_title') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{(isset($siteSetting['favicon']))?url('assets/favicon/'.$siteSetting['favicon']):''}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/libs/select2/select2.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin-assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <script src="{{ asset('admin-assets/vendor/libs/jquery/jquery.js') }}"></script>
    <!-- Helpers -->
    <script src="{{ asset('admin-assets/vendor/js/helpers.js') }}"></script>

    <script src="{{ asset('admin-assets/js/config.js') }}"></script>
    <script src="{{ asset('admin-assets/js/ui-toast.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>

    {{-- <script src="{{ asset('admin-assets/js/ui-toast.js')}}"></script> --}}
    
    @yield('styles')
  </head>


<body style="height: auto;">

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

          @include('partials.menu')
          
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  © <script>
                    document.write(new Date().getFullYear());
                  </script>
                  All Rights Reserved
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('admin-assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/libs/popper/popper.js') }}"></script>

    <script src="{{ asset('admin-assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('admin-assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <script src="{{ asset('admin-assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('admin-assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('admin-assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin-assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admin-assets/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('admin-assets/js/chartjs.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    
    <script>
  $(".select2").select2();

  $('.food_sections').click(function() {
   var thisValue = $(this).val();
   console.log(thisValue);
   if(thisValue=='breakfast'){
        if(this.checked){
              $('.breakfast_menu').removeClass('d-none');
              }else{
                $('.breakfast_menu').addClass('d-none');
              
        }
    }
    if(thisValue=='lunch'){
        if(this.checked){
          $('.lunch_menu').removeClass('d-none');
          }else{
            $('.lunch_menu').addClass('d-none');
          }
    }
      if(thisValue=='dinner'){
        if(this.checked){
          $('.dinner_menu').removeClass('d-none');
          }else{
            $('.dinner_menu').addClass('d-none');
          }
        }
      });
 function FoodsMenu(thisValue){
  
}
</script>
@yield('scripts')
</body>

</html>
