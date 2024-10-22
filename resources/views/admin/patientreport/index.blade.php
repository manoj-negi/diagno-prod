@extends('layouts.adminCommon')
@section('content')


<!-- datatable -->

 <div class="container">

    <div class="row">
   
<div class="col-lg-12">
   <div class="demo-inline-spacing">
   <div class="card">
      <div class=" px-0 mt-0">
         <div class="tab-pane fade active show" id="document">
            <div class="table-responsive text-nowrap">
               <body>
                  <div class="container">
                     <table class="table table-bordered yajra-datatable">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Patient Name</th>
                              <th>Test Name</th>
                              @can('dashboard_support_list')<th>Lab Name</th>@endcan
                             
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <div id="paginationContainer"></div>
                     </table>
                  </div>
               </body>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
    $(document).on('change', ".onChangeStatusSelect", function () {
        $(this).closest('form').submit();
    });
});
$(document).ready(function() {
    $('#pagination').on('change', function() {

        var form = $(this).closest('form');
        $form.find('input[type=submit]').click();
        console.log($form);

    });
    setTimeout(function(){
       $("div.alert").remove();
    }, 3000 ); // 3 secs
});

var table = $('.yajra-datatable').DataTable({
         processing: true,
         serverSide: true,
         dom: 'Bfrtip',
         buttons: [
        {
            extend: 'csvHtml5',
            exportOptions: {
                columns: [0, 1, 2, 3,]
            }
        }
    ],
         
         ajax: "",
         columns: [
             {data: 'id', name: 'id',searchable:true},
             {data: 'name', name: 'name',searchable:true},
             {data: 'test_id', name: 'test_id',searchable:true},
             @if(Auth::user()->roles->contains(1))
            { data: 'hospital_id', name: 'hospital_id', searchable: true },
            @endif
         
             
         ],columnDefs: [{
        targets: 0, // The column index for S.No
        render: function (data, type, row, meta) {
            return meta.row + 1; // Start numbering from 1
        }
    }]
      
     });
</script>
<style>
   .form-select:focus {
    border-color: #007ac2 !important;
}
</style> 

@endsection
