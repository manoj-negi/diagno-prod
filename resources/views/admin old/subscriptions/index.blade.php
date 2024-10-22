@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
           <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{route('subscription.create')}}">Create</a>
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
                                <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="filter">
                                    <option selected="true" disabled="disabled" >{{__('Select Status')}}</option>
                                    <option class="text-dark" value="1" {{request()->get('filter')=='1'?'selected':''}} >Active</option>
                                    <option class="text-dark" value="0" {{request()->get('filter')=='0'?'selected':''}}>De-Active</option>
                                </select >
                                <input type="" id="search" name="search" placeholder="{{__('Search')}}" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" />
                                <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <a class="form-control src-btn" href="{{route('subscription.index')}}"><i class="fa fa-rotate-left"></i></a>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
                <table class="table table-responsive">
                    <tr>
                        <th>S.No</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Days</th>
                        <th>Plan</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                    
                    @foreach($data as $key=> $dat)

                    <tr>
                        <td>{{$data->firstItem() + $key ??''}}</td>
                        <td>{{$dat->title}}</td>
                        <td>{{$dat->price}}</td>
                         <td>{{$dat->days}} Days</td>
                        <td> 
                            @if(!count($dat->plans))
                            -
                            @else
                            @foreach ($dat->plans as $sk=> $item )
                             {{($sk>0)?', ':""}}{{$item ->name ??''}}
                            @endforeach
                             @endif   
                        </td>
                         <td>
                             <span class="badge badgess">{{$dat->status=="0"?'De-Active':'Active'}}</span>
                         </td>
                        <td>

                            <a class="btn btn-primary btn-sm" href="{{route('subscription.edit',$dat->id)}}"
                                style="display:inline-block"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a>
                              <form action="{{route('subscription.destroy',$dat->id)}}" method="post" style="display:inline-block">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger show_confirm btn-sm" type="submit"><i class="fa fa-trash"
                                        aria-hidden="true"></i></button>
                        </td>
                        </form>
                    </tr>
                    @endforeach
                </table>
            </div>

            <div id="pagination" class="pr-4">{{ $data->links() }}</div>
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
     $("document").ready(function(){
    setTimeout(function(){
       $("div.alert").remove();
    }, 3000 ); // 3 s

    });
     });

</script>

@endsection
