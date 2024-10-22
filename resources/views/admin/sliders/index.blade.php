@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
   <div class="col-md-12 text-right">
      <a class="btn btn-primary btn-sm my-3" href="{{route('sliders.create')}}">Create</a>
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
         <form class="index-form">
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
               <div class="col-md-4"></div>
               <div class="col-md-6">
                  <div class="paging-section justify-content-end">
                     
                        <div class="search_bar d-flex">
                        
                           <input type="" id="search" name="search" placeholder="{{ __('Search') }}" class="form-control"value="{{ (request()->get('search') != null) ? request()->get('search') : '' }}"/>
                           <button type="submit" class="form-control src-btn"><i class="fa fa-search"
                              aria-hidden="true"></i></button>
                           <a class="form-control src-btn" href="{{route('sliders.index')}}"><i
                              class="fa fa-rotate-left"></i></a>
                        </div>
                     
                  </div>
               </div>
            </div>
            </form>
            <table class="table table">
               <tr>
                  <th style="width: 10px!important;">S.No</th>
                  <th>Title</th>
                  <th>Image</th>
               
                  <th style="width: 160px!important;">Action</th>
               </tr>
               @forelse($data as $key=> $sliders)
               <tr>
                  <td>{{$data->firstItem() + $key ??''}}</td>
                  <td>{{ucfirst($sliders->title)}}</td>
                  <td>
                <img src="{{ asset('uploads/sliders/' . $sliders->image) }}" style="width: 100px;
    height: 81px ">
           </td>
                 
                  <td class="p-1 pt-3">
                     <a href="{{route('sliders.edit',$sliders->id)}}"
                        class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"
                        aria-hidden="true"></i></a>
                     
                     <form action="{{route('sliders.destroy',$sliders->id)}}" method="post"
                        style="display: inline-block;">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger show_confirm"><i class="fa fa-trash"
                           aria-hidden="true"></i></button>
                     </form>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="5" class="text-center">no data</td>
               </tr>
               @endforelse
            </table>
            <div class="m-3" style="float: right;">
               {{$data->links()}}
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
       $('.onChangeapprovedSelect').on('change', function() {
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
<style>
   .form-select:focus {
    border-color: #007ac2 !important;
}
</style> 
@endsection