
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('questions.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$result->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Question</label>
                                        <input type="text" name="question" class="form-control" value="{{ old('question', isset($result) && isset($result->question) ? $result->question : '') }}" placeholder="Question" />
                                    </div>
                                    @error('question')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Spenish Question</label>
                                        <input type="text" name="spenish_question" class="form-control" value="{{ old('spenish_question', isset($result) && isset($result->spenish_question) ? $result->spenish_question : '') }}" placeholder="Question" />
                                    </div>
                                    @error('spenish_question')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            
                           {{-- <!--  <div class="mb-3">
                                <label class="form-label">Specialities</label>
                                <select name="speciality_id" class="form-select">
                                <option value="">Select Specialities</option>
                                    @foreach($speciality as $specialities)
                                    <option value="{{$specialities->id}}" {{ old('speciality_id', isset($result) && $result->speciality_id==$specialities->id ? 'selected' : '') }}>{{$specialities->name}}</option>
                                    @endforeach
                                </select>
                            </div> --> --}}
                            {{-- @error('speciality_id')
                            <div class="validationclass text-danger pt-2">{{$message}}</div>
                            @enderror --}}
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{isset($result) && $result->status=="1"?'selected':''}}>Active
                                    </option>
                                    <option value="0" {{isset($result) && $result->status=="0"?'selected':''}}>De-Active
                                    </option>
                                </select>
                            </div>
                        </div>
                        <a href="{{route('questions.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
