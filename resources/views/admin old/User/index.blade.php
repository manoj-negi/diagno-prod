@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row">
         <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{route('users.create')}}">Create</a>
            @if ($message = Session::get('success'))
            <div class="alert alert-danger alert-dismissible text-left mt-2">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
            </div>
            @elseif ($message = Session::get('error'))
            <div class="alert alert-danger alert-block text-left mt-2">
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
                                <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="filter">
                                    <option selected="true" disabled="disabled" >{{__('Select Status')}}</option>
                                    <option class="text-dark" value="1" {{request()->get('filter')=='1'?'selected':''}} >Active</option>
                                    <option class="text-dark" value="0" {{request()->get('filter')=='0'?'selected':''}}>De-Active</option>
                                </select>
                                <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="role_filter">
                                    <option selected="true" disabled="disabled" >{{__('Select Role')}}</option>
                                    <option class="text-dark" value="1" {{request()->get('role_filter')=='1'?'selected':''}} >Doctor</option>
                                    <option class="text-dark" value="0" {{request()->get('role_filter')=='0'?'selected':''}}>Patient</option>
                                </select >
                                <input type="" id="search" name="search" placeholder="Search" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                                <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <a class="form-control src-btn" href="{{route('users.index')}}"><i class="fa fa-rotate-left"></i></a>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>

                <table class="table table-responsive">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>status</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                
                    @foreach($userdata as $key=> $userdatas)
                    <tr>
                        <td>{{$userdata->firstItem() + $key ??'' }}</td>
                        <td>{{$userdatas->name}}</td>
                        <td>{{$userdatas->email}}</td>
                        <td><span class="badge badgess">{{$userdatas->status=="0"?'De-Active':'Active'}}</span></td>
                        <td> @foreach ($userdatas->roles as $item )
                            {{($item->role)}}
                            @endforeach</td>

                        <td>
                            <a class="btn btn-primary btn-sm" href="{{route('users.edit',$userdatas->id)}}"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <form action="{{route('users.destroy',$userdatas->id)}}" method="post" style="display:inline-block">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger show_confirm btn-sm" type="submit"><i class="fa fa-trash"
                                        aria-hidden="true"></i></button>
                        </td>
                        </form>
                    </tr>
                    @endforeach
                </table>
                  <div id="pagination">{{{$userdata->links() }}}</div>
            </div>
          
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#pagination').on('change', function() {

        var form = $(this).closest('form');
        $form.find('input[type=submit]').click();
        console.log($form);

    });
    setTimeout(function(){
       $("div.alert").remove();
    }, 3000 );

});
</script>
@endsection