@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{route('patient.create')}}">Create</a>
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
                                <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="filter">
                                    <option selected="true" disabled="disabled" >{{__('Select Status')}}</option>
                                    <option class="text-dark" value="1" {{request()->get('filter')=='1'?'selected':''}} >Active</option>
                                    <option class="text-dark" value="0" {{request()->get('filter')=='0'?'selected':''}}>De-Active</option>
                                    </select >
                                    <input type="" id="search" name="search" placeholder="{{__('Search')}}" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{route('patient.index')}}"><i class="fa fa-rotate-left"></i></a>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>


      

            <table class="table table-responsive tabel-hover">
                <tr>
                    <th>S.No</th>
                    <th style="width: 7% !important;">Name</th>
                    <th style="width: 5% !important;">Email</th>
                    <th>age</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Action</td>
                </tr>
                @foreach($data as $key=> $patientdatas)
                <tr>

                    <td>{{$data->firstItem() + $key ??'' }}</td>
                    <td>
                         @if(strlen($patientdatas->name)>=20)

                        {{substr($patientdatas->name, 0,  20).'...' ??''}}
                        @else

                        {{$patientdatas->name??''}} @endif</td>

                    <td> @if(strlen($patientdatas->email)>=20)

                        {{substr($patientdatas->email, 0,  20).'...' ??''}}

                        @else
                              {{$patientdatas->email??''}}

                       @endif</td>
                    <td>{{$patientdatas->age}}</td>
                    <td>{{$patientdatas->sex}}</td>



                    <td><span class="badge badgess">{{$patientdatas->status=="0"?'De-Active':'Active'}}</span></td>


                    <td class="p-0 pt-2"> <a class="btn btn-primary btn-sm" href="{{route('patient.show',$patientdatas->id)}}"><i
                                class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{route('patient.edit',$patientdatas->id)}}" class="btn btn-primary btn-sm"><i
                                class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                     <form action="{{route('patient.destroy',$patientdatas->id)}}" method="post" style="display: inline-block;">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger show_confirm btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                    </td>

                </tr>

                @endforeach
            </table>
            <div id="pagination">{{{ $data->links() }}}</div>
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
