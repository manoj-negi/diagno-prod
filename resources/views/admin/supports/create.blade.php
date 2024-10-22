
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('supports.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" phone="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', isset($data) && isset($data->name) ? $data->name : '') }}" placeholder="name" required/>
                                    </div>
                                    @error('name')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', isset($data) && isset($data->email) ? $data->email : '') }}" placeholder="email" required/>
                                    </div>
                                    @error('email')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Phone<span class="text-danger">*</span></label>
                                        <input type="number" name="phone" class="form-control" value="{{ old('phone', isset($data) && isset($data->phone) ? $data->phone : '') }}" placeholder="phone" required/>
                                    </div>
                                    @error('phone')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                 
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Subject<span class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control" value="{{ old('subject', isset($data) && isset($data->subject) ? $data->subject : '') }}" placeholder="subject" required/>
                                    </div>
                                    @error('subject')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Message<span class="text-danger">*</span></label>
                                        <input type="text" name="message" class="form-control" value="{{ old('message', isset($data) && isset($data->message) ? $data->message : '') }}" placeholder="message" required/>
                                    </div>
                                    @error('message')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                               
                           
                           
                        </div>
                        <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
