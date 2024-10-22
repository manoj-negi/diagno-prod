@extends('layouts.adminCommon')
@section('content')
 <div class="container">
    <div class="row">
<div class="col-lg-12">
   <div class="demo-inline-spacing mt-3">
   <div class="card">
      <div class="tab-content px-0 mt-0">
         <div class="tab-pane fade active show" id="document">
            <div class="table-responsive text-nowrap">
               <body>
                  <div class="container mt-3">
                     <table class="table table-bordered yajra-datatable">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Mobile</th>
                              <th>date Of Birth</th>
                              <th>Status</th>
                              <th>Action</th>
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
@section('js')
<script>
  
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

         
         ajax: {
        url: '',
        type: 'GET',
        dataSrc: 'data' 
    },
         columns: [
             {data: 'id', name: 'id', searchable:true},
             {data: 'name', name: 'name', searchable:true},
             {data: 'email', name: 'email', searchable:true},
             {data: 'mobile', name: 'mobile', searchable:true},
             {data: 'dob', name: 'dob', searchable:true},
             {data: 'status', name: 'status', searchable:true},
             {data: 'action', name: 'action'},
             
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
@endsection
