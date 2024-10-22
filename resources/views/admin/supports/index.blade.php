@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
    <div class="col-md-12 text-right">
        <a class="btn btn-primary btn-sm my-3" href="{{route('supports.create')}}">Create</a>
        @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                </div>
                @endif
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <form class="index-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                    <div class="paging-section justify-content-start">
                        <select style="width:75px; margin-left: 10px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
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
                                <a class="form-control src-btn" href="{{route('supports.index')}}"><i class="fa fa-rotate-left"></i></a>
                            </div>
                        
                    </div>
</div>
                    </div>
                </div>
                </form>

                <table class="table table table-bordered">
                    <tr>
                    <th style="width: 10px!important;">S.No</th>
                        <th>name</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>subject</th>
                       
                        <th style="width: 160px!important;">Action</td>
                    </tr>
                    @if(count($data)>0)
                        @php
                            isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
                            isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

                            $i = (($page-1)*$paginate)+1;
                        @endphp
                    @endif

                    @forelse($data as $result)
                    <tr>
                        <td>{{$i++ ?? ''}}</td>
                        <td>{{ucfirst($result->name)}}</td>
                        <td>{{ucfirst($result->email)}}</td>
                        <td>{{ucfirst($result->phone)}}</td>
                        <td>{{ucfirst($result->subject)}}</td>

                       
                        <td><a href="{{route('supports.edit',$result->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <form action="{{route('supports.destroy',$result->id)}}" method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete')">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">no data</td></tr>
                    @endforelse
                </table>
                <div id="pagination">{{{ $data->links() }}}</div>
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

