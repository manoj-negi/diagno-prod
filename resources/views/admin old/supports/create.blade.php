
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('supports.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', isset($data) && isset($data->title) ? $data->title : '') }}" placeholder="Title" required/>
                                    </div>
                                    @error('title')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Spenish Title</label>
                                        <input type="text" name="spenish_title" class="form-control" value="{{ old('spenish_title', isset($data) && isset($data->spenish_title) ? $data->spenish_title : '') }}" placeholder="Title" />
                                    </div>
                                    @error('title')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">Issue</label>
                                        <textarea  name="issue" class="form-control" row="2" placeholder="Issue">{{ old('issue', isset($data) && isset($data->issue) ? $data->issue : '') }}</textarea>
        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">Spenish Issue</label>
                                        <textarea  name="spenish_issue" class="form-control" row="2" placeholder="Issue">{{ old('spenish_issue', isset($data) && isset($data->spenish_issue) ? $data->spenish_issue : '') }}</textarea>
        
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">Remark</label>
                                    <textarea  name="remark" class="form-control" row="2" placeholder="Remark">{{ old('remark', isset($data) && isset($data->remark) ? $data->remark : '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">Spenish Remark</label>
                                    <textarea  name="spenish_remark" class="form-control" row="2" placeholder="Remark">{{ old('spenish_remark', isset($data) && isset($data->spenish_remark) ? $data->spenish_remark : '') }}</textarea>
                                </div>
                            </div>
                           </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
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
