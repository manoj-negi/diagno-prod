@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-12 text-right">
      <a class="btn btn-primary btn-sm my-3" href="{{route('lab-category.create')}}">Create</a>
</div>
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
                          <th>Title</th>
                           <th>Description</th>
                           <th>Category Banner</th>
                          <th>Action</th>
                       </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
   $("document").ready(function(){
   setTimeout(function(){
      $("div.alert").remove();
   }, 3000 ); // 3 secs
   
   });
   var table = $('.yajra-datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "", // Your server-side data source URL goes here
    columns: [
        {data: 'id', name: 'id', searchable: true},
        {data: 'title', name: 'title', searchable: true},
        {data: 'description', name: 'description', searchable: true},
        {data: 'image', name: 'image', searchable: false},
        {data: 'action', name: 'action', searchable: false},
    ],
    columnDefs: [{
        targets: 0, // The column index for S.No
        render: function (data, type, row, meta) {
            return meta.row + 1; // Start numbering from 1
        }
    }]
});

</script><style>
   .form-select:focus {
    border-color: #007ac2 !important;
}
</style> 
@endsection
