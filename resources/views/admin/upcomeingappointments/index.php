@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
   <div class="col-md-12 text-right">
      <a class="btn btn-primary btn-sm my-3" href="{{route('lab-test.create')}}">Create</a>
      @if ($message = Session::get('msg'))
      <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
         <button type="button" class="close" data-dismiss="alert">×</button> 
         <strong>{{ $message }}</strong>
      </div>
      @endif
   </div>
   <div class="col-md-12 ">
      <div class="card">
         <div class="col-md-12">
         <form class="index-form">
            <div class="row">
               
                  <div class="col-md-6">
                     <div class="paging-section justify-content-start">
                        <select style="width:75px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
                        @foreach($paginateData as $page)
                        <option value="{{ $page }}" {{ request()->get('pagination') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6 ">
                     <div class="paging-section justify-content-end">
                        <div class="search_bar d-flex">
                          
                           <input type="" id="search" name="search" placeholder="Search" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                           <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                           <a class="form-control src-btn" href="{{route('lab-category.index')}}"><i class="fa fa-rotate-left"></i></a>
                        </div>
                     </div>
                  </div>
              
            </div>
            </form>
            <table class="table table">
               <tr>
                  <th style="width: 10px!important;">S.No</th>
                  <th>Test Name</th>
                  <th>Test Price</th>
                  <th>Inner Test</th>
                  @can('dashboard_support_list')
                  <th>Status</th>
                  @endcan
                  
                  @can('dashboard_labs_test_list')
                    <th>Status</th>
                    @endcan
                  <th style="width: 160px!important;">Action</td>
               </tr>
               @forelse($data as $key=> $result)
               <tr>
                  <td>{{ $key+1}}</td>
                  <td>{{ucfirst($result->test_name)}}</td>
                  <td>₹{{ucfirst($result->amount)}}</td>
                  <td>{!!$result->description!!}</td>
                  @can('dashboard_support_list')
                  <td>
                        <form action="{{url('lab-tests',$result->id)}}">
                        <select name="admin_status" class="form-control onChangeStatusSelect">
                        <option value="pending" {{ isset($result) && $result->admin_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ isset($result) && $result->admin_status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ isset($result) && $result->admin_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                     </select>

                       </form>
                                    </td>
                                    @endcan
                                    @can('dashboard_labs_test_list')
                                    <td>
                                        @if($result->admin_status == 'pending')
                                        <button class="btn btn-xs btn-primary">Pending</button>
                                        @elseif($result->admin_status == 'approved')
                                        <button class="btn btn-xs btn-success">Approved</button>
                                        @else
                                        <button class="btn btn-xs btn-danger">Rejected</button>
                                        @endif
                                    </td>
                                    @endcan
                  <td>
                     <a href="{{route('lab-test.create',$result->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                     <form action="{{route('lab-test.destroy',$result->id)}}" method="post" style="display: inline-block;">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger show_confirm btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                     </form>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="4" class="text-center">no data</td>
               </tr>
               @endforelse
            </table>
         </div>
      </div>
   </div>
</div>
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
</script><style>
   .form-select:focus {
    border-color: #007ac2 !important;
}
</style> 
@endsection