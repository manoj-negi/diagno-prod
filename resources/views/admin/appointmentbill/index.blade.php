@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
    <div class="col-md-12 text-right">
        <a class="btn btn-primary btn-sm my-3" href="{{route('appointments-bills.create')}}">Create</a>
       
    </div>
 <div class="col-md-12">
        <div class="card">
            <div class="col-md-12">
            <form class="index-form">
                <div class="row">
                    <!-- <div class="col-md-6">
                    <div class="paging-section justify-content-start">
                        <select style="width:75px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
                        @foreach($paginateData as $page)
                        <option value="{{ $page }}" {{ request()->get('pagination') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                        </select>
                     </div>
                    </div> -->
                    <!-- <div class="col-md-6">
                  <div class="paging-section justify-content-end">
                    <div class="search_bar d-flex">
                <input type="text" id="search" name="search" placeholder="Search" class="form-control" value="{{ request('search') }}">
                <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                <a class="form-control src-btn" href="{{ route('appointments-bills.index') }}"><i class="fa fa-rotate-left"></i></a>
            </div>
    </div>
</div> -->

                </div>
                </form>

                <table class="table table-bordered labs-datatable">
            <thead>
               <tr>
                  <th style="width: 10px!important;">S.No</th>
                  <th>Title</th>
                  <th>Lab</th>
                  <th>Booking Id</th>
                  <th>amount</th>
                  <th>document file</th>
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
         
         ajax: "",
         columns: [
             {data: 'id', name: 'id'},
             {data: 'title', name: 'title'},
             {data: 'lab', name: 'lab'},
             {data: 'appointment_id', name: 'appointment_id'},
             {data: 'amount', name: 'amount'},
             {data: 'document_file', name: 'document_file'},
             {data: 'action', name: 'action'},
             
         ]
      
     });
</script>



<script>
    $(document).ready(function() {
        // Target the select box by its ID and listen for the change event
        $('.onChangeStatusSelect').on('change', function() {
            // Trigger form submission when the select box value changes
            $(this).closest('form').submit();
        });
    });
</script>
<script>
   $("document").ready(function(){
   setTimeout(function(){
      $("div.alert").remove();
   }, 3000 ); // 3 secs
   
   });
</script>
@endsection

