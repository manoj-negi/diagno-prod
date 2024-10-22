@extends('layouts.adminCommon')
    @section('content')
    <div class="container">
        <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{route('lab-register.create')}}">Create</a>
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
                                    <a class="form-control  src-btn" href="{{route('lab-register.index')}}"><i class="fa fa-rotate-left"></i></a>
                               
                            
                        </div>
                        </div>
                    </div>
                    </form>
                    <table class="table table">
                        <tr>
                            <th style="width: 10px!important;">S.No</th>
                            <th>Lab Name</th>
                            <th>email</th>
                            <th>Postal Code</th>
                            <th>City</th>

                            <th style="width: 160px!important;">Action</th>
                            </tr>
                            @forelse($data as $key => $value)
                                <tr data-entry-id="{{ $value->id }}">
                                    <td>{{$data->firstItem() + $key ??''}}</td>
                                    <td>
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div>
                                                    <span>
                                                        @if(!empty($value->hospital_logo))
                                                            <img src="{{ url('uploads/lab-register/'.$value->hospital_logo) }}"
                                                                alt="" class="rounded-circle avatar me-2"
                                                                style="box-shadow: 0px -1px 9px -3px #000000;width: 35px;height: 35px; padding: 2px; margin-right: 12px !important;">
                                                        @else
                                                            <img src="{{ url('uploads/hospital/49912demo.jpg') }}" alt="user-avatar"
                                                                class="rounded-circle avatar me-2" id="uploadedAvatar"
                                                                style="box-shadow: 0px -1px 9px -3px #000000;width: 35px;height: 35px; padding: 2px; margin-right: 12px !important;">
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="emp_name text-truncate">{{ $value->name ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                   
                                    <td>{{ $value->email ?? '' }}</td>
                                    <td>{{ $value->postal_code ?? '--' }}</td>
                                    <td>{{ $value->city_id ?? '--' }}</td>
                                    
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{ route('lab-register.edit', $value->id) }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    
                                        <form action="{{route('lab-register.destroy',$value->id)}}" method="post" style="display: inline-block;">
                                            @csrf
                                            @method('delete')
                                            <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="{{ trans('global.delete') }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                    </form>
                                    </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">no data</td></tr>
                        @endforelse
                    </table>
                </div>
                <div id="pagination">{{{ $data->links() }}}</div>
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
<script>
    $(document).ready(function() {
        // Target the select box by its ID and listen for the change event
        $('.onChangeStatusSelect').on('change', function() {
            // Trigger form submission when the select box value changes
            $(this).closest('form').submit();
        });
    });
</script>
<style>
   .form-select:focus {
    border-color: #007ac2 !important;
}
</style> 
@endsection

