@extends('layouts.adminCommon')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css?family=Roboto:100');


/** page **/
.cssloader {
    padding-top: calc(45vh - 25px);
    position: fixed;
    z-index: 99999;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: #00000070;
}

/** loader **/
.sh1 {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 50px 50px 0 0;
  border-color: #681f4c   transparent transparent transparent;
  margin: 0 auto;
  animation: shk1 1s ease-in-out infinite normal;
}

.sh2 {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 0 0 50px 50px;
  border-color: transparent  transparent #1b717a transparent ;
  margin: -50px auto 0;
  animation: shk2 1s ease-in-out infinite alternate;
}

/** animation starts here **/
@keyframes shk1 {
  0% {
    transform: rotate(-360deg);
  }  
  
  100% {
  }
}

@keyframes shk2 {
  0% {
    transform: rotate(360deg);
  }
  100% {
  }
}

.lt {
  color: #fff;
  font-family: 'Roboto', sans-serif;
  margin: 30px auto;
  text-align: center;
  font-weight: 100;
  letter-spacing: 10px;
}
</style>
<div class="container">
<div class="row pb-2">
   <div class="col-md-12 text-right">
      <!-- <?php  $url = Request::segment(1); ?>
      <a class="btn btn-success btn-sm my-3" data-bs-toggle="modal" data-bs-target="#importTests" style="color: white;">Import Tests</a>
      <a class="btn btn-primary btn-sm my-3" href='{{url("radiology/create?url=$url")}}'>Create</a>
      @if ($message = Session::get('msg'))
      <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
         <button type="button" class="close" data-dismiss="alert">×</button> 
         <strong>{{ $message }}</strong>
      </div>
      @endif -->
   </div>
   <div class="cssloader d-none">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">Importing data.... Do not close</h4>
</div>
   <div class="modal fade" id="importTests" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
         <form id="importForm" action="{{url('import-csv')}}" method="POST" enctype="multipart/form-data">
         @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
               <div class="col-12 mb-3">
                  <label for="nameBasic" class="form-label">Select Lab</label>
                  <select name="lab"  class="form-control mr-1" style="color:#697a8d!important;" fdprocessedid="hkqlg7">
                  <option value="1">{{ env('APP_NAME', 'DiagnoMitra') }}</option>
                  @foreach($labs ?? [] as $item)
                  <option value="{{$item->id ?? ''}}">{{$item->name ?? ''}}</option>
                  @endforeach
                  </select>
               </div>
               <div class="col-12 mb-3">
                  <label for="nameBasic" class="form-label">Select CSV File</label>
                  <input type="file" name="file" class="form-control" accept=".csv">
               </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Import</button>
            </form>
            </div>
         </div>
      </div>
      </div>
   <div class="col-md-12  mt-5">
     
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
                        </div>
                        <div class="col-md-2 mt-3">
                           @if(Auth::user()->roles->contains(1) && Request::segment(1) != 'diagno-tests')
                           <select name="lab" id="" class="form-control mr-1" style="color:#697a8d!important;" fdprocessedid="hkqlg7">
                              <option value="">Select Lab</option>
                              <option value="1" {{ app('request')->input('lab') == '1' ? 'selected' : '' }}>{{ env('APP_NAME', 'DiagnoMitra') }}</option>
                              @foreach($labs ?? [] as $item)
                              <option value="{{$item->id ?? ''}}" {{ app('request')->input('lab') == $item->id ? 'selected' : '' }}>{{$item->name ?? ''}}</option>
                              @endforeach
                           </select>
                           @endif
                        </div>
                        <div class="col-md-3 mt-3 d-flex">
                           <input type="" id="search" name="search" placeholder="Search" class="form-control" value="{{ app('request')->input('search') }}" placehoder="Search" fdprocessedid="tdutng">
                           <button type="submit" class="form-control src-btn" fdprocessedid="l5a1oq"><i class="fa fa-search" aria-hidden="true"></i></button>
                           <a class="form-control  src-btn" href="{{url('radiology')}}"><i class="fa fa-rotate-left"></i></a>
                        </div>
                     </div>
                  </div>
               </div>
               </form>
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th style="width: 10px!important;">S.No</th>
                        <th>User Name</th>
                        <th>Wallet Balance</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Status</th>
                        <th style="width: 160px!important;">Action</th>
                     </tr>
                  </thead>
                  <tbody >
                     @foreach($data as $key => $value)
                     <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->user_id->user->name ?? ''}}</td>
                        <td>{{$value->wallet_balance ?? ''}}</td>
                        <td>{{$value->credit ?? ''}}</td>
                        <td>{{$value->debit ?? ''}}</td>
                        <td>{{$value->status ?? ''}}</td>
                        @if(Auth::user()->roles->contains(1)) 
                        <td>
                           @if(Auth::user()->roles->contains(1))
                              @if($value->lab_id != Auth::user()->id)
                                {{isset($value->labDetails) ? $value->labDetails->name : '-'}} 
                              @else
                                  {{ env('APP_NAME', 'DiagnoMitra') }}
                              @endif
                           @elseif(Auth::user()->roles->contains(4))
                                 @if(Auth::user()->id == $value->lab_id)
                                   {{Auth::user()->name}}
                                 @else
                                 {{ env('APP_NAME', 'DiagnoMitra') }}
                                 @endif
                           @endif

                        </td>
                        @endif
                        <td>{{number_format($value->amount ?? 0,2)}}</td>
                        <td>
                           @if(Auth::user()->roles->contains(1))
                              <a href="{{route('radiology.edit', $value->id) }}?url=radiology" class="btn btn-primary btn-sm mr-1">
                                 <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                              </a> 
                              <form action="{{route('radiology.destroy', $value->id)}}" method="POST" style="display: inline-block;">
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                 <input type="hidden" name="_method" value="delete">
                                 <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                 </a>
                              </form>
                           @elseif(Auth::user()->roles->contains(4) && Auth::user()->id == $value->lab_id)
                              <a href="{{route('radiology.edit', $value->id)}}" class="btn btn-primary btn-sm">
                                 <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                              </a>
                              <form action="{{route('radiology.destroy', $value->id)}}" method="POST" style="display: inline-block;">
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                 <input type="hidden" name="_method" value="delete">
                                 <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                 </a>
                              </form>
                           @endif
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
                  
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

    document.getElementById('importForm').addEventListener('submit', function(e) {
      $('.cssloader').removeClass('d-none');
    });
</script>
@endsection