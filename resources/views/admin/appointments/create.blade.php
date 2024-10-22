@extends('layouts.adminCommon')
@section('content')

<div class="container">
@if ($message = Session::get('msg'))
      <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
         <button type="button" class="close" data-dismiss="alert">×</button> 
         <strong>{{ $message }}</strong>
      </div>
      @endif
    <div class="row justify-content">
        <div class="col-md-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('appointments.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{isset($data)?$data->id:''}}" name="id">
                            <div class="col-md-6">
                            <label class="form-label">Bookin Id<span class="text-danger">*</span></label>
                            <input type="text" name="appointment_id" class="form-control" value="{{ old('appointment_id', $data->id ?? '') }}" required />
                            @error('appointment_id')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{ $message }}</span>
                            @enderror
                        </div>
                           <div class="col-md-6">
                            <label class="form-label">Patient Name<span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control" value="{{ old('patient_name', $data->patient->name ?? '') }}" required />
                            @error('patient_name')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Patient Age<span class="text-danger">*</span></label>
                            <input type="number" max="100" min="0" name="age" class="form-control" value="{{old('age',  $data->patient->age ?? '')}}" required />
                            @error('age')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Patient Age<span class="text-danger">*</span></label>
                            <input type="text" max="100" min="0" name="gender" class="form-control" value="{{old('age',  $data->patient->age ?? '')}}" required />
                            @error('age')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Patient Gender<span class="text-danger">*</span></label>
                            <input type="text" max="100" min="0" name="gender" class="form-control" value="{{old('age',  $data->patient->sex ?? '')}}" required />
                            @error('age')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        

                        <div class="col-md-6">
                            <label class="form-label">Patient Contact Number<span class="text-danger">*</span></label>
                            <input type="number" name="contact" class="form-control" value="{{old('contact', $data->patient->number ?? '')}}" required />
                            @error('contact')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Patient Full Address<span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{old('address', $data->patient->address ?? '')}}" required />
                            @error('address')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Test <span class="text-danger">*</span></label>
                            <input type="text" name="test_type" class="form-control" value="{{old('test_type', $data->test->test_name ?? '')}}" required />
                            @error('test_type')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <input type="text" name="test_type" class="form-control" value="{{old('test_type', $data->status ?? '')}}" required />
                            @error('test_type')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div> -->
                        

                       
                         
                        <div class="col-md-6">
                            <label class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="number"  min="0" name="amount" class="form-control" value="{{old('amount', $data->test->amount ?? '')}}" required />
                            @error('amount')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <!-- </div> -->
                        



                @can('dashboard_labs_test_list')
                <div class="col-md-6">
                        <div class="mb-3">
                       
                        <input value="pending" name="admin_status" type="hidden">
                        </div>
                @endcan

                        <div class="mt-3 mb-3">
                        <a href="{{ route('test-request.index') }}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Send Test Request</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content');
CKEDITOR.config.allowedContent = true;
</script>
