@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
   <div class="col-md-12 text-right">
      @if ($message = Session::get('msg'))
      <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
         <button type="button" class="close" data-dismiss="alert">×</button> 
         <strong>{{ $message }}</strong>
      </div>
      @endif
   </div>
   <div class="col-md-12 mt-5">
         <div class="card">
            <div class="col-md-12">
               <form action="">
               <div class="row">
                  <div class="col-md-1">
                     <div class="paging-section justify-content-start">
                        <select style="width:75px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()" fdprocessedid="hk0hs">
                           <option value="10" {{ app('request')->input('pagination') == '10' ? 'selected' : '' }}>10</option>
                           <option value="30" {{ app('request')->input('pagination') == '30' ? 'selected' : '' }}>30</option>
                           <option value="50" {{ app('request')->input('pagination') == '50' ? 'selected' : '' }}>50</option>
                           <option value="70" {{ app('request')->input('pagination') == '70' ? 'selected' : '' }}>70</option>
                           <option value="100" {{ app('request')->input('pagination') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-11">
                     <div class="paging-section justify-content-end row">
                        <div class="col-md-2 mt-3">
                           <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Start Date" class="form-control" value="{{app('request')->input('start_date')}}" name="start_date" fdprocessedid="t3ftiu">
                        </div>
                        <div class="col-md-2 mt-3">
                           <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="End Date" class="form-control" value="{{app('request')->input('end_date')}}" name="end_date" fdprocessedid="t3ftiu">
                        </div>
                        <div class="col-md-2 mt-3">
                           <select  class="form-select form-select" name="status" fdprocessedid="hk0hs">
                              <option value="">Select Status</option>
                              @foreach($bookingStatus as $value)
                                 <option value="{{$value}}" {{app('request')->input('status') == $value ? 'selected' : ''}}>{{ucfirst($value)}}</option>
                                 @endforeach
                           </select>
                        </div>
                        @if(Auth::user()->roles->contains(1))
                        <div class="col-md-2 mt-3">
                          
                           <select name="lab" id="" class="form-control mr-1" style="color:#697a8d!important;" fdprocessedid="hkqlg7">
                              <option value="">Select Lab</option>
                              @foreach($labs ?? [] as $item)
                              <option value="{{$item->id ?? ''}}" {{ app('request')->input('lab') == $item->id ? 'selected' : '' }}>{{$item->name ?? ''}}</option>
                              @endforeach
                           </select>
                        </div>
                        @endif
                        <div class="col-md-3 mt-3 d-flex">
                           <input type="" id="search" name="search" placeholder="Search" class="form-control" value="{{ app('request')->input('search') }}" placehoder="Search" fdprocessedid="tdutng">
                           <button type="submit" class="form-control src-btn" fdprocessedid="l5a1oq"><i class="fa fa-search" aria-hidden="true"></i></button>
                           <a class="form-control  src-btn" href="{{url('appointments')}}"><i class="fa fa-rotate-left"></i></a>
                        </div>
                     </div>
                  </div>
               </div>
               </form>
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th>S.No</th>
                        <th>Patient Name</th>
                        @if(Auth::user()->roles->contains(1))<th>Lab Name</th>@endif
                        <th>Booking Type</th>
                        <th>Booking Date</th>
                        <th>Report File</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($data as $key => $item)
                     <tr>
                        <td>{{$key+1}}</td>
                        <td> <a href="{{url('patient',$item->patient_id)}}"> {{isset($item->patient) ? $item->patient->name : '-'}} </a></td>
                        @if(Auth::user()->roles->contains(1)) <td><a href="{{url('lab/'.$item->hospital_id.'/edit')}}">{{isset($item->hospital) ? $item->hospital->name : '-'}}</a></td>@endif
                        <td>{{ucfirst($item->test_type)}}</td>
                        <td>{{date('d F Y H:i A',strtotime($item->created_at))}}</td>
                        <td>
                           @csrf
                           <button type="button" class="btn btn-xs btn-primary upload-button" data-bs-target="#exampleModalupload" user-id="{{$item->id}}">
                              Upload
                          </button></td>
                          <td>
                           
                          <form action="{{url('appointments-status-update', $item->id)}}" method="post">
                              @csrf
                              <select name="status" class="form-control onChangeStatusSelect" style="color: #697a8d!important;">
                                 @foreach($bookingStatus as $value)
                                 <option value="{{$value}}" {{$item->status == $value ? 'selected' : ''}}>{{ucfirst($value)}}</option>
                                 @endforeach
                                  
                              </select>
                             
                          </form>
                          </td>
                          <td>
                           <a class="btn btn-primary btn-sm" href="{{route('appointments.show', $item->id)}}">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                          </a>
                              <form action="{{route('appointments.destroy', $item->id)}}" method="POST" style="display: inline-block;">
                                  @csrf
                                  <input type="hidden" name="_method" value="delete">
                                  <button type="submit" class="text-white btn btn-danger show_confirm btn-sm">
                                      <i class="fa fa-trash" aria-hidden="true"></i>
                                  </button>
                              </form>
                          </td>
                     </tr>
                     @endforeach
                  </tbody>
                  <div id="paginationContainer"></div>
               </table>
            </div>
            <div id="pagination">{!! $data->links() !!}</div>
         </div>
      
   </div>
</div>
<style>
   .form-select:focus {
   border-color: #007ac2 !important;
   }
</style>
@endsection
@section('js') 
<script>
   $(document).ready(function() {
   $(document).on('change', ".onChangeStatusSelect", function () {
       $(this).closest('form').submit();
   });
   });
   
     var table = $('.labs-datatable').DataTable({
        processing: true,
        serverSide: true,
        bProcessing: true,
        sProcessing: true,
        bServerSide: true,
        dom: 'Bfrtip',
        buttons: [
       {
           extend: 'csvHtml5',
           exportOptions: {
               columns: [0, 1, 2, 3,] 
           }
       }
   ],
   
        paging: true, // Enable pagination
        pageLength: 10, // Set the number of rows per page
        ajax: "",
        columns: [
            {data: 'id', name: 'id', searchable: true},
            {data: 'package_name', name: 'test_name', searchable: true},
            {data: 'lab', name: 'lab', searchable: true},
            {data: 'amount', name: 'amount', searchable: true},
            {data: 'action', name: 'action', searchable: false},   
        ],columnDefs: [{
       targets: 0, // The column index for S.No
       render: function (data, type, row, meta) {
           return meta.row + 1; // Start numbering from 1
       }
   }]
       
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