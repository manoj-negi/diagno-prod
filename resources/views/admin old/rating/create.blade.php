
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content ">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('ratingreviews.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}"> 

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="" class="form-label">Select Doctor<span class="text-danger">*</span></label>
                                <select name="doctor_id"  class="  mb-2  mr-sm-2 form-select"
                                 id="">
                                 <option>Select Doctor</option>
                                    @foreach($doctor as $key => $result)
                                    <option value="{{$key}}" {{isset($data) && $key==$data->doctor_id ? 'selected' : ''}}>{{$result}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="" class="form-label">Select Patient<span class="text-danger">*</span></label>
                                <select name="patient_id"  class=" mb-2  mr-sm-2  form-select"
                                 id="" required>
                                    <option>Select Patient</option>
                                    @foreach($patient as $key => $patients)
                                    <option value="{{$key}}" {{isset($data) && $key==$data->patient_id ? 'selected' : ''}}>{{$patients}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-md-12">
                            <label class="form-label">Rating</label>
                            <select name="ratings" id="" class="mb-2  mr-sm-2 form-select">
                                <option value="1" {{isset($data) && $data->ratings=="1"?'selected':''}}>1 star</option>
                                <option value="2" {{isset($data) && $data->ratings=="2"?'selected':''}}>2 star</option>
                                <option value="3" {{isset($data) && $data->ratings=="3"?'selected':''}}>3 star</option>
                                <option value="4" {{isset($data) && $data->ratings=="4"?'selected':''}}>4 star</option>
                                <option value="5" {{isset($data) && $data->ratings=="5"?'selected':''}}>5 star</option>
                            </select>
                        </div>  
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Review</label>
                                <textarea  name="review" class="form-control" row="2" placeholder="review">{{ old('review', isset($data) && isset($data->review) ? $data->review : '') }}</textarea>
                                @error('review')
                                        <span class="text-danger validationclass" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                        </div>
                            <div class="col-md-6">
                                <label class="form-label">Spenish Review</label>
                                <textarea  name="spenish_review" class="form-control" row="2" placeholder="review">{{ old('spenish_review', isset($data) && isset($data->spenish_review) ? $data->spenish_review : '') }}</textarea>
                                @error('spenish_review')
                                        <span class="text-danger validationclass" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                        </div>
                    </div>
                        
                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm mt-3">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
                      </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
