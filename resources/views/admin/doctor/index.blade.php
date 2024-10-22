@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{route('doctor.create')}}">Create</a>
           @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                </div>
                @endif
        </div>
        <div class="col-md-12 pt-2">
            <div class="card">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6 ">
                        <div class="paging-section justify-content-end">
                        <form class="index-form">
                                <div class="search_bar d-flex">
                                    <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="status">
                                        <option selected="true" disabled="disabled" >{{__('Select Status')}}</option>
                                        <option class="text-dark" value="1" {{request()->get('status')=='1'?'selected':''}} >Active</option>
                                        <option class="text-dark" value="0" {{request()->get('status')=='0'?'selected':''}}>De-Active</option>
                                    </select >
                                      <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="filter">
                                        <option selected="true" disabled="disabled" >{{__('Approvel Status')}}</option>
                                        <option class="text-dark" value="approved"{{request()->get('app_filter')=='approved'?'selected':''}}>Approved</option>
                                        <option class="text-dark" value="pending" {{request()->get('app_filter')=='pending'?'selected':''}}>Pending</option>
                                        <option class="text-dark" value="rejected" {{request()->get('app_filter')=='rejected'?'selected':''}}>Rejected</option>
                                    </select >
                                    <input type="" id="search" name="search" placeholder="{{__('Search')}}" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{route('doctor.index')}}"><i class="fa fa-rotate-left"></i></a>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>

                <table class="table table-responsive">
                    <tr>
                        <th style="width: 5% !important;">S.No</th>
                        <th style="width: 5% !important;">Name</th>
                        <th>Email</th>
                        <th>Approvel status</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                   
                    @foreach($doctorData as $key=> $result)
                    <tr>
                        <td>{{$doctorData->firstItem() + $key ??''}}</td>
                        <td>
                             @if(strlen($result->name)>=10)

                        {{substr($result->name, 0,  10).'...' ??''}}
                        @else

                        {{$result->name??''}}

                        @endif</td>
                        <td>
                              @if(strlen($result->email)>=20)

                        {{substr($result->email, 0,  20).'...' ??''}}
                        @else

                        {{$result->email??''}}

                        @endif
                        </td>
                       
                        <td><span class="badge {{ $color=['approved' => 'badge-success', 'pending' => 'badge-info', 'rejected' => 'badge-danger'][$result->is_approved] }}" >{{$result->is_approved}}</span></td>
                        <td><span class="badge badgess">{{$result->status=="0"?'In Active':'Active'}}</span></td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{route('doctor.edit',$result->id)}}"
                                style="display:inline-block"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a>
                            <a class="btn btn-primary btn-sm" href="{{route('doctor.show',$result->id)}}"
                                style="display:inline-block"><i class="fa fa-eye" aria-hidden="true"></a></i>
                            <form action="{{route('doctor.destroy',$result->id)}}" method="post" style="display:inline-block">
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

            <div id="pagination">{{$doctorData->withQueryString()->links() }}</div>
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
    }, 3000 ); // 3 secs

});
</script>
@endsection
