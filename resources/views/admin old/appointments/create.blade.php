
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content ">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('appointments.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}"> 
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="" class="form-label">Select Doctor <span class="text-danger">*</span></label>
                                <select name="doctor_id"  class="  mb-2  mr-sm-2 form-select"
                                 id="" required>
                                  <option value="">{{__('Select Doctor')}}</option>
                                    @foreach($doctor as $key => $result)
                                    <option value="{{$key}}" {{isset($data) && $key==$data->doctor_id ? 'selected' : ''}}>{{$result}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">Select Patient <span class="text-danger">*</span></label>
                                <select name="patient_id"  class=" mb-2  mr-sm-2  form-select"
                                 id="" required>
                                 <option value="">{{__('Select Patient')}}</option>
                                    @foreach($patient as $key => $patients)
                                     <option value="{{$key}}" {{isset($data) && $key==$data->patient_id ? 'selected' : ''}}>{{$patients}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{old('date',  $data->date ??'')}}" required />
                            @error('date')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                        </div>
                          <div class="col-md-6">
                            <label class="form-label">Time<span class="text-danger">*</span></label>
                            <input type="time" name="time" class="form-control" value="{{ old('time',  $data->time ??'')}}" required />
                            @error('time')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                        </div>
                    </div>
                        <div class="row mb-2">
                                <div class="col-md-6 pt-3">
                                    <label class="form-label" >By Trun Time<span class="text-danger">*</span></label>
                                    <select name="by_turn_time" class="form-select text-muted mr-1" required>
                                        <option class="text-dark" value="0" {{isset($data) && $data->by_trun=="0" ? "selected" : ''}}>By Trun
                                         </option>
                                        <option class="text-dark" value="1" {{isset($data) && $data->is_time=="1" ? "selected" : ''}}>Is Time
                                         </option>
                                    </select>
                                     @error('by_turn_time')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                                 </div>                           
                           
                                <div class="col-md-6 pt-3">
                                    <label class="form-label">Consult Type <span class="text-danger">*</span></label>
                                     <select name="consult_type" class="form-select text-muted mr-1" required>
                                        <option class="text-dark" value="1" {{isset($data) && $data->is_virtual=="1" ? "selected" : ''}}>&nbsp;&nbsp;Is Virtual
                                         </option>
                                        <option class="text-dark" value="0" {{isset($data) && $data->is_physical=="0" ? "selected" : ''}}>&nbsp;&nbsp;Is Physical
                                         </option>
                                    </select>
                                     @error('consult_type')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{isset($data) && $data->status=="1"?'selected':''}}>Active
                                        </option>
                                        <option value="0" {{isset($data) && $data->status=="0"?'selected':''}}>De-Active
                                        </option>
                                    </select>
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
@endsection
