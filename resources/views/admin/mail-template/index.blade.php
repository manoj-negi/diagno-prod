@extends('layouts.adminCommon')
@section('content')
{{-- @can('role_create') --}}
<div class="container">
<div style="margin-bottom: 20px;" class="row">
    <div class="col-lg-12">
        <h4 class="card-title">
            <!--  -->
            <a class="btn btn-primary text-light mt-2" style="float: right;" href="{{ route("mails-template.create") }}">
                {{ trans('') }} {{ trans('Mail-template') }}
            </a>
        </h4>
    </div>
</div>
{{-- @endcan --}}
@if (\Session::has('msg'))
    <div class="alert alert-dark alert-dismissible mb-3" role="alert">
        {!! \Session::get('msg') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="card">
    <div class="card-header">
    <div class="row">
        <div class="12"></div>
            <div class="col-12">
                <div class="input-group-prepend d-flex">
                    <input type="text" name="search" style="margin-right: 5px;" id="search" class="search form-control" placeholder="{{ __('Search')}}" value="{{ (request()->get('search'))??''}}"/>
                    <button class="input-group-text" id="inputGroup-sizing-sm"><i class='bx bx-search'></i></button>
                </div>
            </div>
</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="10">#id</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Message Category</th>
                        <!-- <th>Message Form</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mailTemplates as $key => $value)
                        <tr data-entry-id="{{ $value->id }}">
                            <td>{{ $mailTemplates->firstItem() + $key }}</td>
                            <td>{{ $value->from_name ?? '' }}</td>
                            <td>{{ $value->subject?? '' }}</td>
                            <td>{{ $value->category?? '' }}</td>
                            <!-- <td>{{ $value->message ?? '' }}</td> -->
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('mails-template.edit', $value->id) }}">
                                    {{ trans('Edit') }}
                                </a>
                                <form action="{{ route('mails-template.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('Delete') }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3" style="float: right;">
        {{$mailTemplates->links()}}
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
</script>
@endsection
