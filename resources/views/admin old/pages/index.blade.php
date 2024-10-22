@extends('layouts.adminCommon')
@section('content')
<div class="container"></br>
<div class="row">
     <div class="col-md-12 text-right">
         <a class="btn btn-primary btn-sm" href="{{route('pages.create')}}">Create</a></br></br>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9">
                    </div>
                    <div class="col-md-3">
                        <div class="paging-section">
                            <form>
                                <div class="search_bar d-flex">
                                    <input type="" id="search" name="search" class="form-control"
                                        value="{{(request()->get('search') != null)? request()->get('search'):''}}"
                                        placehoder="Search" />&nbsp;
                                    
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{route('pages.index')}}"><i
                                            class="fa fa-rotate-left"></i></a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <table class="table table-responsive">
                    <tr>
                        <th>S.No</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Action</th>
                    </tr>
                    @if(count($data)>0)
							@php 
								isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
								isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

								$i = (($page-1)*$paginate)+1; 
							@endphp
                    @endif
                    @foreach($data as $dat)
                    <tr>
                        <td>{{$i++ ?? ''}}</td>
                        <td>{{$dat->title}}</td>
                        <td>{{$dat->slug}}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{route('pages.edit',$dat->id)}}"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <form action="{{route('pages.destroy',$dat->id)}}" method="post"
                                onsubmit=" return confirm('Are you sure you want to delete')"
                                style="display:inline-block">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"
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


<script type="text/javascript">
$(document).ready(function() {

    $('#pagination').on('change', function() {

        var $form = $(this).closest('form');

        //$form.submit();

        $form.find('input[type=submit]').click();

        console.log($form);

    });

});
</script>
@endsection