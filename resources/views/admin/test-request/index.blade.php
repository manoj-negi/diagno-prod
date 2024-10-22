@extends('layouts.adminCommon')
@section('content')


<!-- datatable -->

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
                             
                              @can('dashboard_support_list')<th>Lab Name</th>@endcan
                              <th>Patient Name</th>
                              <th>contact</th>
                              <th>amount</th>
                              @can('dashboard_support_list')
                              <th>Action</th>
                              @endcan
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
         
         ajax: "",
         columns: [
             {data: 'id', name: 'id',searchable:true},
             @if(Auth::user()->roles->contains(1))
            { data: 'lab_id', name: 'lab_id', searchable: true },
            
            @endif
            
             {data: 'patient_name', name: 'patient_name',searchable:true},
             {data: 'contact', name: 'contact',searchable:true},
             {data: 'amount', name: 'amount',searchable:true},
             @if(Auth::user()->roles->contains(1))
             {data: 'action', name: 'action'},
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
