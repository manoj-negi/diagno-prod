<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>{{isset($title)?$title.' - ':''}}{{ $siteSetting['name'] }}</title>
    <!-- Favicon -->
       <link rel="icon" type="image/x-icon" href="{{url('Images/favicon').'/'.$siteSetting['favicon']}}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fork-awesome@1.2.0/css/fork-awesome.min.css" integrity="sha256-XoaMnoYC5TH6/+ihMEnospgm0J1PM/nioxbOUdnM8HY=" crossorigin="anonymous">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{url('vendor/fonts/boxicons.css')}}" />
    <link rel="stylesheet" href="{{url('csss/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{url('csss/custom.css')}}"/>
    <link rel="stylesheet" href="{{url('css/style.css')}}"/>
    <!-- <link rel="stylesheet" href="{{url('css/select2.css')}}"/> -->

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{url('vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{url('css/demo.css')}}" />
    

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{url('vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{url('vendor/libs/apex-charts/apex-charts.css')}}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{url('vendor/js/helpers.js')}}"></script>
     <!--Page Datatable-->
     <link rel="stylesheet" href="{{url('vendor/libs/apex-charts/apex-charts.css')}}" />
    <link rel="stylesheet" href="{{ asset('datatable/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <!-- Page CSS -->
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <script src="{{url('js/jquery.min.js')}}"></script>
    <script src="{{url('js/bootstrap.mins.js')}}"></script>

    <script src="{{url('js/data-table.min.js')}}"></script>    
    <link rel="stylesheet" href="{{url('js/datatable.min.css')}}" />
    <script src="{{url('js/config.js')}}"></script>

    
 
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  p.help-block {
    color: red;
}
</style>
</head>

<body>

    <!-- model layout -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModalupload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Upload Report</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                
                <form method="POST" action="{{url('upload-report')}}" 
                enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="userID1" value="" name="user_id">
                        <div class="row">
                        <div class="form-group mb-3 {{ $errors->has('report_image') ? 'has-error' : '' }}">
                          <div class="col-md-12">
                          <label class="form-label" for="report_image">{{ __('Report File') }}</label>
                        <input style="width:100%!important;" id="report_image" class="form-control" accept="PDF/*" type="file"  name="report_image"   class="form-control" value="">
                        @if($errors->has('report_image'))
                           <p class="help-block">
                                 {{ $errors->first('report_image') }}
                           </p>
                        @endif
                        <!-- <input class="form-control" type="text" name="name">
                        <input class="form-control" type="file" name="image"> -->
                        </div>
                        </div>
                        </div>
                        <button class="btn btn-xs btn-primary">Submit</button>
                     
                     </form>
                </div>
                <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div> -->
              </div>
            </div>
          </div>
    <!-- model layout -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.includes.sidebar')
            <!-- Layout container -->
            <div class="layout-page">
               @include('layouts.includes.navHeader')
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{url('vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{url('vendor/libs/popper/popper.js')}}"></script>
    <script src="{{url('vendor/js/bootstrap.js')}}"></script>
    <script src="{{url('vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <!-- <script src="{{('vendor/ckeditor/ckeditor.js')}}"></script> -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>


   
    <script src="{{url('vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{url('vendor/libs/apex-charts/apexcharts.js')}}"></script>
     <!-- Page JS -->
    <script src="{{url('js/dashboards-analytics.js')}}"></script>

    <!-- datatable js -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap JavaScript (if using Bootstrap) -->
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"> </script>
   <script src = "https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"> </script>

    <!-- datatable js -->
    <!-- Main JS -->
    <script src="{{url('js/main.js')}}"></script>
    <script src="{{url('jss/select2.js')}}"></script>
    <script src="{{ asset('datatable/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-checkboxes-jquery/datatables.checkboxes.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons/buttons.html5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons/buttons.print.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    
    <style>
      table.dataTable thead .sorting:before{
        content: "\2191" !important;
        top: 12px !important;
      }
      table.dataTable thead .sorting:after {
        content: "\2193" !important;
        top: 15px !important;
      }
      button.dt-button.buttons-csv.buttons-html5 {
          margin-top: 15px !important;
      }
    </style>
    <!-- <script>
         $(".select2").select2();
         
            $(".upload-button").on("click", function(){
                var dataId = $(this).attr("user-id");
                if(dataId != ''){
                $('#userID1').val(dataId);
                $('#exampleModalupload').modal('show');
                }
            });
    </script> -->
    <script>
    $(".select2").select2();

    $(document).on('click', ".upload-button", function () {
        var dataId = $(this).attr("user-id");
        if (dataId !== '') {
            $('#userID1').val(dataId);
            $('#exampleModalupload').modal('show');
        }
    });

$(document).on('click', ".show_confirm", function (event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");

          event.preventDefault();

          swal({

              title: `Are you sure you want to delete this record?`,

              text: "If you delete this, it will be gone forever.",

              icon: "warning",

              buttons: true,

              dangerMode: true,

          })

          .then((willDelete) => {

            if (willDelete) {

              form.submit();

            }

          });

      });
        $( document ).ready(function() {
            $(".select2").select2();
            // $('.select2test').select2({
            // ajax: {
            //     url: "{{ url('/get-test') }}",
            //     dataType: 'json',
            //     delay: 250,
            //     data: function (params) {
            //         return {
            //             q: params.term,
            //             page: params.page || 1
            //         };
            //     },
            //     processResults: function (data, params) {
            //         params.page = params.page || 1;
            //         return {
            //             results: data.items,
            //             pagination: {
            //                 more: (params.page * 10) < data.total_count 
            //             }
            //         };
            //     },
            //     cache: true
            // },
            // placeholder: 'Search for an test...',
            // minimumInputLength: 1, 
            // escapeMarkup: function (markup) { return markup; }, 
            // templateResult: formatItem, 
            // templateSelection: formatItemSelection
        // });

      function formatItem(item) {
          if (item.loading) return item.text;
          var markup = "<div class='select2-result-item'>";
          markup += item.package_name;
          markup += "</div>";

          return markup;
      }
      function formatItemSelection(item) {
          return item.package_name;
      }

    $( ".delete-record" ).click(function() {
      var value = $(this).attr("data");
      var url      = window.location.href; 
      var data = url+'/'+value;
      $('.delete-form').attr('action',data);
      
    });
            
        });


            function addElement(){
                     
                var elem = document.querySelector('#elem1');
                var clone = elem.cloneNode(true);
                    clone.removeAttribute('id'); 
                
                    var removediv = document.createElement('div');
                     removediv.classList.add('col-2','alg-self-center');
                     
                     var removebtn=document.createElement('a');
                     removebtn.classList.add('btn','btn-danger','btn-sm','removebtn');
                       
                     removebtn.textContent='Remove';

                     removebtn.onclick=function(){
                       let parent= removebtn.parentNode.parentNode;
                        parent.remove();
                     }
                     removediv.appendChild(removebtn);

                     clone.appendChild(removediv);
                      
                     document.getElementById('append').appendChild(clone);
                    //  elem.after(clone);
            }
            function removenode(a){

              let parent=a.parentNode.parentNode;
              parent.remove();
            }

        // document.addEventListener("DOMContentLoaded", function() {
        //     const tagifyBasicEl = document.querySelector("#model_tags");
        //     const TagifyBasic = new Tagify(tagifyBasicEl); 
        // });

    // var spinner = $('#loader');
    // spinner.hide();
    //   $('form').submit(function(e) {
    //     spinner.show();
  
    //   })
</script>
@yield('js')
   
 <style>
ul.pagination {
    /* display: !important; */
    display: flex!important;
    justify-content: flex-end!important;
}
li.paginate_button.page-item {
    margin: 0px;
}
li.page-item {
    margin-left: 2px;
}
.paginate_button 
   {
   margin-left: 10px;
   cursor:pointer !important;
   }
   .paginate_button 
   {
   margin-right: 10px;
   cursor:pointer !important;
   }
   .table.dataTable{
    width: 100% !important;
}
a.paginate_button.current {
    color: #696cff !important;
}
 
.dataTables_wrapper .dataTables_paginate .paginate_button{
  margin-bottom: 10px;
}

.demo-inline-spacing .text-nowrap {
    white-space: nowrap !important;
    padding: 15px 30px 8px 15px;
}

 </style>

</body>

</html>