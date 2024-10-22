@extends('layouts.adminCommon')
@section('content')
<style>
    .offer_image img {
    width: 47px;
    height: 47px;
}
.main_dates {
    margin-top: 14px !important;
}
</style>
<div class="container"></br>
<div class="row">
     <div class="col-md-12 text-right">
         <a class="btn btn-primary btn-sm" href="{{route('offers.create')}}">Create</a></br></br>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="card">
            <div class="col-md-12">
                <form>
                <div class="row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-2 mt-2 main_dates">
                           <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Start Date" class="form-control" value="{{app('request')->input('start_date')}}" name="start_date" fdprocessedid="t3ftiu">
                        </div>
                        <div class="col-md-2 mt-2 main_dates">
                           <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="End Date" class="form-control" value="{{app('request')->input('end_date')}}" name="end_date" fdprocessedid="t3ftiu">
                        </div>
                    
                    <div class="col-md-3">
                        <div class="paging-section">
                           
                                <div class="search_bar d-flex">
                                    <input type="" id="search" name="search" class="form-control"
                                        value="{{(request()->get('search') != null)? request()->get('search'):''}}"
                                        placehoder="Search" />&nbsp;
                                    
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{route('offers.index')}}"><i
                                            class="fa fa-rotate-left"></i></a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
                <table class="table table table-bordered">
                    <tr>
                    <th style="width: 10px!important;">S.No</th>
                        <th>Offer Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th style="width: 160px!important;">Action</td>
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
                        <td>{{$dat->offer_name ?? ''}}</td>
                        <td>  @if($dat->validity_end)
                                {{ \Carbon\Carbon::parse($dat->validity)->format('Y-m-d') }}
                            @else
                                No data found
                            @endif</td>
                        <td>  @if($dat->validity_end)
                                {{ \Carbon\Carbon::parse($dat->validity_end)->format('Y-m-d') }}
                            @else
                                No data found
                            @endif</td>
                            <td>
                                @php
                                $currentDate = \Carbon\Carbon::now();
                                $validityStart = \Carbon\Carbon::parse($dat->validity);
                                $validityEnd = \Carbon\Carbon::parse($dat->validity_end);
                            @endphp
                            
                            @if($currentDate->between($validityStart, $validityEnd))
                               <p class="text-success">Active</p>
                            @elseif($currentDate->lessThan($validityStart))
                                <p class="text-info">Coming Soon</p>
                            @else
                                
                                <p class="text-danger">Expire</p>
                            @endif
                            </td>
                            <td>@if(isset($dat) && !empty($dat->image))
                                <div class="offer_image">
                                <img class="img-fluid rounded my-4"
                                src="{{url('uploads/package',$dat->image ?? '')}}" 
                                alt="User avatar">
                                </div>
                                 @endif
                                 </td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('offers.edit', $dat->id) }}"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <form action="{{route('offers.destroy',$dat->id)}}" method="post"
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