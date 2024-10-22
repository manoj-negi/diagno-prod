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
                                </div>
                                <div class="col-md-7">
                                    <div class="paging-section justify-content-end">
                                        <form class="index-form">
                                            <div class="search_bar d-flex">
                                                <input type="text" id="search" name="search" placeholder="Search"
                                                    class="form-control"
                                                    value="{{ (request()->get('search') != null) ? request()->get('search') : '' }}" />
                                                <button type="submit" class="form-control src-btn"><i
                                                        class="fa fa-search" aria-hidden="true"></i></button>
                                                <a class="form-control src-btn" href="{{ route('amount.index') }}"><i
                                                        class="fa fa-rotate-left"></i></a>
                                            </div>
                                    </div>
                                </div>
                            </div>
        </form>
        <table class="table table table-bordered">
            <tr>
                <th style="width: 10px!important;">S.No</th>
                <th>Available</th>
                <th>Package Name</th>
                <th>Profile</th>
                <th>Amount</th>
                <th style="width: 160px!important;">Action</td>
            </tr>
            @forelse($data as $key => $result)
            <tr>
                <td>{{$data->firstItem() + $key ?? ''}}</td>
                <td>
                    @if(isset($result->is_selected))
                    <input type="checkbox" name="is_selected" value="1" {{ $result->is_selected ? 'checked' : '' }}>
                    @else
                    Availability not selected
                    @endif
                </td>
                <td>{{ isset($result->package_name) ? $result->package_name : 'Package not selected' }}</td>
                <td>{{ isset($result->profile) ? $result->profile : 'Profile not selected' }}</td>
                <td>
                    <form action="{{route('amount.destroy', $result->id)}}" method="post"
                        style="display: inline-block;">
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
                <td colspan="6" class="text-center">No Data Found</td>
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
    $(document).ready(function () {
        $('#pagination').on('change', function () {

            var form = $(this).closest('form');
            $form.find('input[type=submit]').click();
            console.log($form);


        });
        setTimeout(function () {
            $("div.alert").remove();
        }, 3000); // 3 secs

    });

</script>
<style>
    .form-select:focus {
        border-color: #007ac2 !important;
    }
</style>
@endsection