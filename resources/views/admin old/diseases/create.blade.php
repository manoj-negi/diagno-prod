
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('diseases.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{old('name', $data->name ?? '')}}" placeholder="Title" required />
                                    </div>
                                    @error('name')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Spenish Title<span class="text-danger">*</span></label>
                                        <input type="text" name="spenish_name" class="form-control" value="{{old('spenish_name', $data->spenish_name ?? '')}}" placeholder="Title"  />
                                    </div>
                                    @error('spenish_name')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-description">Description</label>
                                        <textarea  name="description" class="form-control" row="2" placeholder="Description" >{{old('description', $data->description ?? '')}}</textarea>
                                        @error('description')
                                        <span class="validationclass text-danger pt-4">{{$message}}</span>
                                        @enderror  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-description">Spenish Description</label>
                                        <textarea  name="spenish_description" class="form-control" row="2" placeholder="Description" >{{old('spenish_description', $data->spenish_description ?? '')}}</textarea>
                                        @error('spenish_description')
                                        <span class="validationclass text-danger pt-4">{{$message}}</span>
                                        @enderror  
                                    </div>
                                </div>
                            </div>
                          <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-causes">Causes</label>
                                    <textarea  name="causes" class="form-control" row="2" placeholder="Causes">{{old('causes', $data->causes ?? '')}}</textarea>
                                    @error('causes')
                                    <span class="validationclass text-danger pt-4">{{$message}}</span>
                                    @enderror                          
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-causes">Spenish Causes</label>
                                    <textarea  name="spenish_causes" class="form-control" row="2" placeholder="Causes">{{old('spenish_causes', $data->spenish_causes ?? '')}}</textarea>
                                    @error('spenish_causes')
                                    <span class="validationclass text-danger pt-4">{{$message}}</span>
                                    @enderror                          
                                </div>
                            </div>
                          </div>
                            
                            <div class="mb-3">
                                <label  class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{isset($data) && $data->status=="1"?'selected':''}}>Active
                                    </option>
                                    <option value="0" {{isset($data) && $data->status=="0"?'selected':''}}>De-Active
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="" class="form-label">Symptoms<span class="text-danger">*</span></label>
                                <select name="symptom[]"  class="multiple  mb-2  mr-sm-2 select2 form-select"
                                multiple="multiple" id="" required>
                                    @foreach($symptom as $key => $result)
                                    <option value="{{$key}}"{{(in_array($key, old('symptom', [])) || isset($data) && $data->symptom->contains($key)) ? 'selected' :''}}>{{$result}}</option>
                                    @endforeach
                                </select>
                                  @error('symptom')
                                <span class="validationclass text-danger pt-4">{{$message}}</span>
                                @enderror 
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="" class="form-label">Medication<span class="text-danger">*</span></label>
                                <select name="medication[]"  class="multiple form-control mb-2  mr-sm-2 select2 form-select"
                                multiple="multiple" id="" required>
                                    @foreach($medication as $key => $medications)
                                    <option value="{{$key}}"{{(in_array($key, old('medication', [])) || isset($data) && $data->medications->contains($key)) ? 'selected' :''}}>{{$medications}}</option>
                                    @endforeach
                                </select>
                                  @error('medication')
                                <span class="validationclass text-danger pt-4">{{$message}}</span>
                                @enderror 
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="" class="form-label">Medicaltest</label>
                                <select name="medicaltest[]"  class="multiple form-control mb-2  mr-sm-2 select2 form-select"
                                multiple="multiple" id="">
                                    @foreach($medicaltest as $key => $test)
                                    <option value="{{$key}}"{{(in_array($key, old('medicaltest', [])) || isset($data) && $data->medicaltests->contains($key)) ? 'selected' :''}}>{{$test}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm mt-2">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm  mt-2">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
