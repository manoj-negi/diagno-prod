@extends('layouts.adminCommon')
@section('content')
<div class="container"></br>
<div class="row">
     <div class="col-md-12 text-right">
         <a href="{{route('medicaltest.create')}}" class="btn btn-primary btn-sm">Create</a></br></br>
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
                                    <!-- <select class="form-control" id="filter" name="filter">
                                        <option>--Select-Status--</option>
                                        <option value="0" {{request()->get('filter')==0?'selected':''}}>In Active
                                        </option>
                                        <option value="1" {{request()->get('filter')==1?'selected':''}}>Active</option>
                                    </select>&nbsp; -->
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{route('medicaltest.index')}}"><i
                                            class="fa fa-rotate-left"></i></a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
            <table class="table table-responsive">
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>diseases</th>
                    <th>Doctor's Speciality</th>
                    <th>Action</td>
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
                    <td>{{$dat->name}}</td>
                    <td><img src="{{url('Images').'/'.$dat->image}}" width="50px" height="50px" /></td>
                    <!-- <td><span class="badge badgess">{{$dat->status=="1"?'Active':'In Active'}}</span></td> -->
                    <td> @foreach ($dat->test as $item )
                        {{$item->name}},
                        @endforeach</td>
                    <td> @foreach ($dat->tests as $item )
                        {{$item->name}},
                        @endforeach</td>
                    <td><a href="{{route('medicaltest.edit',$dat->id)}}" class="btn btn-primary btn-sm"><i
                                class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <form action="{{route('medicaltest.destroy',$dat->id)}}" style="display: inline-block;"
                            onsubmit="return confirm('Are you sure you want to delete')" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"
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