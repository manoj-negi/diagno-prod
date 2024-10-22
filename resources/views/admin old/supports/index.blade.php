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
                                </select >       
                                <input type="" id="search" name="search" placeholder="Search" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                                <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <a class="form-control src-btn" href="{{route('supports.index')}}"><i class="fa fa-rotate-left"></i></a>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>

                <table class="table table-responsive tabel-hover">
                    <tr>
                        <th>S.No</th>
                        <th>Title</th>
                        <th>Issue</th>
                        <th>Remark</th>
                        <th>Status</th>
                        <th>Action</td>
                    </tr>
                    @if(count($data)>0)
                        @php
                            isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
                            isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

                            $i = (($page-1)*$paginate)+1;
                        @endphp
                    @endif

                    @foreach($data as $result)
                    <tr>
                        <td>{{$i++ ?? ''}}</td>
                        <td>{{ucfirst($result->title)}}</td>
                        <td> @if (strlen($result->issue)>=20){
                            <p class="text-break" style="max-width:500px;">{{substr($result->issue, 0,  20) ??''}}..</p>
                        }
                        @else

                        {{$result->issue??''}}
                        
                        @endif
                            
                        </td>
                        <td> @if (strlen($result->remark)>=20){
                            <p class="text-break" style="max-width:500px;">{{substr($result->remark, 0,  20) ??''}}..</p>
                        }
                        @else

                        {{$result->remark??''}}
                        
                        @endif
                            
                        </td>
                        <td><span class="badge badgess">{{$result->status=="0"?'De-Active':'Active'}}</span></td>
                        <td><a href="{{route('supports.edit',$result->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <form action="{{route('supports.destroy',$result->id)}}" method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete')">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
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

