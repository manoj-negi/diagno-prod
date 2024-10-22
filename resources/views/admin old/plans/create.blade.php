
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('plans.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', isset($data) && isset($data->name) ? $data->name : '') }}" placeholder="Title" required />
                            </div>
                            {{-- @error('title') --}}
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1" {{isset($data) && $data->status=="1"?'selected':''}}>Active
                                    </option>
                                    <option value="0" {{isset($data) && $data->status=="0"?'selected':''}}>De-Active
                                    </option>
                                </select>
                            </div>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
