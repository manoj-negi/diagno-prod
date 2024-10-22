@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm  my-3" href="{{route('role.create')}}">Create</a>
            @if ($message = Session::get('msg'))
            <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
         <div class="col-md-12">
        <div class="card">
            <div class="col-md-12">
                <div class="paging-section">
                    <table class="table table">
                        <tr>
                              <th style="width: 10px!important;">S.No</th>
                            <th>Role</th>
                            <th>permission</th>
                               <th style="width: 160px!important;">Action</td>

                
                        @foreach($role as $key=> $roles)
                        <tr>
                            <td>{{$role->firstItem() + $key ??''}}</td>
                            <td>{{$roles->role}}</td>
                            <td>@foreach ($roles->permissions as $ek=> $permission )
                               {{($ek>0)?', ':""}}{{$permission ->permission ??''}}
                            @endforeach</td>
                              <td>
                                <a class="btn btn-primary btn-sm" href="{{route('role.edit',$roles->id)}}"><i
                                        class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <form action="{{route('role.destroy',$roles->id)}}" method="post" style="display:inline-block">
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
                <div id="pagination">{{{$role->links() }}}
            </div>
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