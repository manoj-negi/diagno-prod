@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row">  
        <div class="col-md-12 text-right">
              <a class="btn btn-primary btn-sm  my-4" href="{{route('permission.create')}}">Create</a>
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
            <div class="col-md-12">
                <div class="paging-section">
                    
                        <table class="table table-responsive">
                            <tr>
                                <th>S.No</th>
                                <th>Permission</th>
                                <th>Action</th>
                            </tr>
                            
                            @foreach($permission as $key=> $permissions)
                            <tr>
                                <td>{{$permission->firstItem() + $key ??''}}</td>
                                <td>{{$permissions->permission}}</td>
                                <td>

                                    <a class="btn btn-primary btn-sm" href="{{route('permission.edit',$permissions->id)}}">
                                        <i  class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                    <form action="{{route('permission.destroy',$permissions->id)}}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger show_confirm btn-sm" type="submit"><i class="fa fa-trash"
                                                aria-hidden="true"></i></button>
                              </form>
                                </td>
                    </tr>
                    @endforeach
                    </table>
                </div>
                {{-- <div id="pagination">{{{ $data->links() }}}
            </div> --}}
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