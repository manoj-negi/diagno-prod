
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('type-of-consultations.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$result->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">consultation Name</label>
                                        <input type="text" name="consultation_name" class="form-control" value="{{ old('consultation_name', isset($result) && isset($result->consultation_name) ? $result->consultation_name : '') }}" placeholder="consultation Name" required />
                                    </div>
                                    @error('consultation_name')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Spanish Consultation Name</label>
                                        <input type="text" name="spanish_consultation_name" class="form-control" value="{{ old('spanish_consultation_name', isset($result) && isset($result->spanish_consultation_name) ? $result->spanish_consultation_name : '') }}" placeholder="Spanish Consultation Name" />
                                    </div>
                                    @error('spanish_consultation_name')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
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
                        <a href="{{route('type-of-consultations.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
