
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('appointments-refer.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">appointment<span class="text-danger">*</span></label>
                            <select name="appointment_id" class="form-control"required>                                   
                                  <option value="">appointment selected</option>
                                    @foreach($hospital_appointment as $val)
                                    @if ($val->refer_code !== null)
                                    <option value="{{$val->id}}" {{isset($data) && $val->id == $data->appointment_id ? 'selected' : ''}}>#{{$val->id}}</option>
                                    
                                    @endif  @endforeach
                                </select>
                            @error('appointment_id')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Refer Code<span class="text-danger">*</span></label>
                            <select name="refer_code" class="form-control"required>                                   
                                  <option value="">Refer Code selected</option>
                                    @foreach($refercode as $key => $val)
                                    <option value="{{$val->refer_code}}" {{isset($data) && $val->refer_code == $data->refer_code ? 'selected' : ''}}>#{{$val->refer_code}}</option>
                                    @endforeach
                                </select>
                            @error('refer_code')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                            
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Amount<span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" value="{{ old('amount', isset($data) && isset($data->amount) ? $data->amount : '') }}" required />
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
