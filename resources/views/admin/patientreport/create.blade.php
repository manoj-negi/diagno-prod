@extends('layouts.adminCommon')
@section('content')
<div class="container">
      <div class="row justify-content">
          <div class="col-md-12 mt-4">
            <div class="card">
            <div class="card-body">
                <form action="{{route('patient-report.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$result->id ?? ''}}" />
                   <div class="row">
                   <!-- <div class="col-md-6 ">
                        <label for="" class="form-label">Appointment Id	<span class="text-danger">* </span></label>
                                    <select name="appointment_id"class="form-select"
                                       required>
                                       @foreach($appointment as $key=>$data)
                                        <option value="{{$data->id}}" {{isset($result) && $result->appointment_id == $data->id ? 'selected' : ''}}>{{$data->id}}</option>
                                     
                                        @endforeach
                                    </select>
                            @error('appointment_id')
                            <span class="validationclass text-danger" style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                       </div> -->

                      
                        <div class="col-md-6 ">
                        <label for="" class="form-label">Patient<span class="text-danger">* </span></label>
                                    <select name="patient_id"  class="form-select"
                                       required>
                                       @foreach($Patient as  $key=>$item)
                                        <option value="{{$item->name}}" {{isset($result) && $result->patient_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                     
                                        @endforeach
                                    </select>
                            @error('patient_id')
                            <span class="validationclass text-danger" style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Report Title<span class="text-danger">*</span></label>
                            <input type="text" name="report_title" class="form-control" value="{{old('report_title', $result->report_title ?? '')}}" required />
                            @error('report_title')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-6">
                <div class="form-group mb-3 {{ $errors->has('image') ? 'has-error' : '' }}">
                <label class="form-label" for="name">Report*</label>
                <input type="file" id="report_image" name="report_image"  class="form-control" value="{{ old('report_image', isset($data) ? $data->report_image : '') }}" @if(!isset($data)) required @endif>
                @if($errors->has('report_image'))
                    <p class="help-block">
                        {{ $errors->first('report_image') }}
                    </p>
                @endif
            </div>
           </div>
            @if(isset($data) && !empty($data->report_image))
            <img src="{{url('uploads/patientreport',$data->report_image)}}" alt="" class="mt-2 mb-2" width="140" height="140">
            <input type="hidden" value="{{$data->report_image ?? ''}}" name="old_report_image">
              @endif
</div> 
                    <a class="btn btn-warning btn-sm mt-4" href="{{ url()->previous() }}">Back</a>
                    <button type="submit" value="submit" class="btn btn-primary btn-sm mt-4">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function() {
    $('.multiple').select2({

        placeholder: "Select",
        allowClear: true
    });
});
</script>
</body>

@endsection
