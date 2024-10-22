@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row pb-2">
        <div class="col-md-12 text-right">
        <!-- <a class="btn btn-primary btn-sm my-3" href="{{route('ratingreviews.create')}}">Create</a> -->
             @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                </div>
                @endif
        </div>
        <form class="index-form">
    <div class="row">
        <div class="col-md-12 text-right">
       
            
        </div>
        <div class="col-md-12 pt-2">
            <div class="card">
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-5">

                    <div class="paging-section justify-content-start">
                        <select style="width:75px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
                        @foreach($paginateData as $page)
                        <option value="{{ $page }}" {{ request()->get('pagination') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                        </select>
                     </div>
                </div>         
                        <div class="col-md-7">
                        <div class="paging-section justify-content-end">
        <form class="index-form">
            <div class="search_bar d-flex">
            <!-- <select name="patient" id="" class="form-control mr-1" style="color:#697a8d!important;">
    <option value="">Select patient</option>
    @foreach($patient as $item)
        <option value="{{ $item->id }}" {{ request()->get('patient') == $item->id ? 'selected' : '' }}>
            {{ isset($item->name) ? $item->name : $item->name }}
        </option>
    @endforeach
     </select> -->
            <select name="hospital" id="" class="form-control mr-1" style="color:#697a8d!important;">
    <option value="">Select hospital</option>
    @foreach($hospital as $item)
        <option value="{{ $item->id }}" {{ request()->get('hospital') == $item->id ? 'selected' : '' }}>
            {{ isset($item->hospital->name) ? $item->hospital->name : $item->name }}
        </option>
    @endforeach
     </select>

          

            <input type="text" id="search" name="search" placeholder="Search" class="form-control" value="{{ (request()->get('search') != null) ? request()->get('search') : '' }}" />
                <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                <a class="form-control src-btn" href="{{ route('ratingreviews.index') }}"><i class="fa fa-rotate-left"></i></a>
            </div>
      
    </div>
</div>

                    </div>
                    </form>

                    <table class="table table table-bordered">
                        <tr>
                        <th style="width: 10px!important;">S.No</th>
                        @can('dashboard_support_list')<th>Lab Name</th>@endcan
                            <th>patient Name</th>
                            <!-- <th>Rating</th> -->
                            <th>Review</th>
                            <th style="width: 160px!important;">Action</td>
                        </tr>
                        
                        @forelse($data as $key => $result)
    <tr>
        <td>{{$data->firstItem() + $key ?? ''}}</td>
        @can('dashboard_support_list')  <td>{{ isset($result->hospital->name) ? $result->hospital->name : 'Lab not selected' }}</td>@endcan
        <td>{{ isset($result->patient->name) ? $result->patient->name : 'patient not selected' }}</td>
        <!-- <td>{{ $result->ratings ?? '---' }} Stars</td> -->
        <td>{{ (strlen($result->review) > 0) ? Str::limit($result->review, 20) : '---' }}</td>


        <td>
            <!-- <a href="{{route('ratingreviews.edit', $result->id)}}" class="btn btn-primary btn-sm">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </a> -->
            <form action="{{route('ratingreviews.destroy', $result->id)}}" method="post" style="display: inline-block;">
                @csrf
                @method('delete')
                <button class="btn btn-danger show_confirm btn-sm">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No Review Found</td>
    </tr>
@endforelse

                    </table>
                </div>
                <div id="pagination">{{{ $data->links() }}}</div>
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
    }, 3000 ); // 3 secs
    
});

</script>

<style>
    .form-select:focus {
    border-color: #007ac2 !important;
}
</style>
@endsection

