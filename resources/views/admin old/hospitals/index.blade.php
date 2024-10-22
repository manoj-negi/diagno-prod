@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row pb-2">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{route('hospitals.create')}}">Create</a>
             @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                </div>
                @endif
        </div>
    <div class="col-md-12">
        <div class="card">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 ">
                    <div class="paging-section justify-content-end">
                     <form class="index-form">
                            <div class="search_bar d-flex">
                                    <select class="form-select text-muted mr-1"
                                        onchange="document.querySelector('.index-form').submit()" id="filter"
                                        name="filter">
                                        <option selected="true" disabled="disabled">{{__('Select Status')}}</option>
                                        <option class="text-dark" value="1"
                                            {{request()->get('filter')=='1'?'selected':''}}>Active</option>
                                        <option class="text-dark" value="0"
                                            {{request()->get('filter')=='0'?'selected':''}}>De-Active</option>
                                    </select>
                                    <input type="" id="search" name="search" placeholder="{{__('Search')}}" class="form-control"
                                        value="{{(request()->get('search') != null)? request()->get('search'):''}}"
                                        />
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{route('hospitals.index')}}"><i
                                            class="fa fa-rotate-left"></i></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="table table-responsive">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>No of Doctors</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    
                    @foreach($data as $key=> $hospital)
                    <tr>
                        <td>{{$data->firstItem() + $key ??''}}</td>
                        <td>{{ucfirst($hospital->name)}}</td>
                        <td>{{ucfirst($hospital->city)}}</td>
                        <td> {{$hospital->users_count}}</a></td>
                        <td>{{substr($hospital->description, 0,  20) ??''}}</td>
                        <td><span class="badge badgess">{{$hospital->status=="1"?'Active':'De-Active'}}</span></td>
                        <td class="p-0 pt-2"><a href="{{route('hospitals.edit',$hospital->id)}}"
                                class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a>
                          <a href="{{route('hospitals.show',$hospital->id)}}" class="btn btn-primary btn-sm"><i
                                    class="fa fa-eye" aria-hidden="true"></i></a>
                            <form action="{{route('hospitals.destroy',$hospital->id)}}" method="post"
                                style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger show_confirm"><i class="fa fa-trash"
                                        aria-hidden="true"></i></button>
                            </form>
                        </td>
                    </tr>

                    @endforeach
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
@endsection