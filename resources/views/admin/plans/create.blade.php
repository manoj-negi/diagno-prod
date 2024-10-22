
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
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">title<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', isset($data) && isset($data->title) ? $data->title : '') }}"  required />
                                @if($errors->has('title'))
                                        <p class="help-block">
                                            {{ $errors->first('title') }}
                                        </p>
                                    @endif
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Commission Percentage<span class="text-danger">*</span></label>
                                <input type="number" name="commission_percentage" class="form-control" value="{{ old('commission_percentage', isset($data) && isset($data->commission_percentage) ? $data->commission_percentage : '') }}" required />
                                @if($errors->has('commission_percentage'))
                                        <p class="help-block">
                                            {{ $errors->first('commission_percentage') }}
                                        </p>
                                    @endif
                            </div>
                            </div>
                           
                            
                            <div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">Validity<span class="text-danger">*</span></label>
        <select name="validity" class="form-control form-select mr-1">
            <option value="30" {{ isset($result) && $result->validity == "30" ? 'selected' : '' }}>1 Month</option>
            <option value="90" {{ isset($result) && $result->validity == "90" ? 'selected' : '' }}>3 Month</option>
            <option value="180" {{ isset($result) && $result->validity == "180" ? 'selected' : '' }}>6 Month</option>
            <option value="365" {{ isset($result) && $result->validity == "365" ? 'selected' : '' }}>12 Month</option>
        </select>
        @if($errors->has('validity'))
                                        <p class="help-block">
                                            {{ $errors->first('validity') }}
                                        </p>
                                    @endif
    </div>
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
