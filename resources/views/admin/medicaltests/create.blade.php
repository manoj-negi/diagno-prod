
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    <form action="{{route('medicaltests.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="title">{{__('Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{old('name', $data->name ?? '')}}" placeholder="Title" required/>
                                </div>
                                @error('name')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="title">{{__('Spenish Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="spenish_name" id="title" class="form-control" value="{{old('spenish_name', $data->spenish_name ?? '')}}" placeholder="Title" required/>
                                </div>
                                @error('spenish_name')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="description">{{__('Description')}}</label>
                                    <textarea  name="description" id="description" class="form-control" row="2" placeholder="Description">{{old('description', $data->description ?? '')}}</textarea>
    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="description">{{__('Spenish Description')}}</label>
                                    <textarea  name="spenish_description" id="description" class="form-control" row="2" placeholder="Description">{{old('spenish_description', $data->spenish_description ?? '')}}</textarea>
    
                                </div>
                            </div>
                        </div>
                           
                            <div class="mb-3">
                                <label class="form-label" for="status">{{__('Status')}}</label>
                                <select name="status" id="status" class="form-select">
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
