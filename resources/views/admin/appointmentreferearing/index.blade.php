@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
    <div class="col-md-12 text-right">
        <!-- <a class="btn btn-primary btn-sm my-3" href="{{route('appointments-refer.create')}}">Create</a> -->
         @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                </div>
                @endif
    </div>
 <div class="col-md-12 mt-3">
        <div class="card">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                   
                    </div>
                    <div class="col-md-8">
                    <div class="paging-section justify-content-end">
                     <form class="index-form">
                            <div class="search_bar d-flex">
                            <select name="filter" class="form-control mr-1" style="color:#697a8d!important;" >
                             <option value="">Days Filter</option>
                             <option value="1">1 Day</option>
                             <option value="10">10 Days</option>
                             <option value="30">30 Days</option>
                             <option value="60">2 Months</option>
                             <option value="180">6 Months</option>
                             <option value="365">12 Months</option>
                         </select>
                        
                        

                                <input type="" id="search" name="search" placeholder="{{__('Search')}}" class="form-control" value ="{{(request()->get('search') != null)? request()->get('search'):''}}" placehoder="Search"/>
                                <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <a class="form-control src-btn" href="{{route('appointments-refer.index')}}"><i class="fa fa-rotate-left"></i></a>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>

                <table class="table table tabel-hover">
                    <tr>
                    <th style="width: 10px!important;">S.No</th>
                        <th>Referrer Name</th>
                        <th>Refer Code</th>
                         <th>Total Refer</th>
                       
                        <th style="width: 160px!important;">Action</td>
                    </tr>
                    @forelse($data as $key=> $result)
                    <tr>
                        <td>{{$data->firstItem() + $key ??'' }}</td>
                       
                      
                        <td>{{ (isset($result->name) ? $result->name : '-') }}</td>
                        <td>{{ (isset($result->refer_code) ? $result->refer_code : '-') }}</td>
                        <td>
                                @if(isset($result->referearnning))
                                    {{ $result->referearnning->count() }}
                                @else
                                    0
                                @endif
                        </td>
                        <td>
                           
                                <a href="{{ route('appointments-refer.show', $result->refer_code) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                        
                        </td>



                    </tr>
                    @empty
                    <tr><th class="text-center" colspan="7">No Data</th></tr>
                @endforelse
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

