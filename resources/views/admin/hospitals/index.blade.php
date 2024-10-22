@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row">
   <div class="col-md-12 text-right">
      <a class="btn btn-primary btn-sm my-3" href="{{route('lab.create')}}">Create</a>
      @if ($message = Session::get('msg'))
      <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
         <button type="button" class="close" data-dismiss="alert">×</button> 
         <strong>{{ $message }}</strong>
      </div>
      @endif
   </div>
   <div class="col-md-12">
      <div class="card">
         <div class="col-md-12">
            <!-- <form class="index-form">
               <div class="row">
                  <div class="col-md-2">
                     <div class="paging-section justify-content-start">
                        <select style="width:75px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
                        @foreach($paginateData as $page)
                        <option value="{{ $page }}" {{ request()->get('pagination') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                     <div class="paging-section justify-content-end">
                        <select name="filter" id="" class="form-control mr-1" style="color:#697a8d!important;">
                           <option value="" >Select Status</option>
                           <option value="approved" {{ request()->get('is_approved') == 'approved' ? 'selected' : ''}}>Approved</option>
                           <option value="pending" {{ request()->get('is_approved') == 'pending' ? 'selected' : ''}}>Pending</option>
                           <option value="rejected" {{ request()->get('is_approved') == 'rejected' ? 'selected' : ''}}>Rejected</option>
                        </select>
                        <select name="category" id="" class="form-control mr-1" style="color:#697a8d!important;">
                           <option value="">Select Category</option>
                        </select>
                        <input type="" id="search" name="search" placeholder="search" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                        <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                        <a class="form-control  src-btn" href="{{route('lab.index')}}"><i class="fa fa-rotate-left"></i></a>
                     </div>
                  </div>
               </div>
            </form> -->
            <table class="table table-bordered labs-datatable">
            <thead>
               <tr>
                  <th style="width: 10px!important;">S.No</th>
                  <th>Lab Name</th>
                  <th>email</th>
                  <th>Postal Code</th>
                  <th style="width: 160px!important;">Action</th>
               </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<script>
   $("document").ready(function(){
       setTimeout(function(){
          $("div.alert").remove();
       }, 3000 ); // 3 secs
   
   });
</script>

<style>
   .form-select:focus {
   border-color: #007ac2 !important;
   }
   
</style>
@endsection
@section('js')
<script>
    var table = $('.labs-datatable').DataTable({
         processing: true,
         serverSide: true,
         dom: 'Bfrtip',
         buttons: [
        {
            extend: 'csvHtml5',
            exportOptions: {
                columns: [0, 1, 2, 3, 4] 
            }
        }   
    ],
         ajax: "",
         columns: [
             {data: 'id', name: 'id', searchable: true},
             {data: 'lab_name', name: 'lab_name', searchable: true},
             {data: 'email', name: 'email', searchable: true},
             {data: 'postal_code', name: 'postal_code', searchable: true},
             {data: 'action', name: 'action', searchable: false},
         ],
         columnDefs: [{
        targets: 0, // The column index for S.No
        render: function (data, type, row, meta) {
            return meta.row + 1; // Start numbering from 1
        }
    }]
         
      
     });

   $(document).ready(function() {
       // Target the select box by its ID and listen for the change event
       $('.onChangeStatusSelect').on('change', function() {
           // Trigger form submission when the select box value changes
           $(this).closest('form').submit();
       });
   });
</script>
@endsection