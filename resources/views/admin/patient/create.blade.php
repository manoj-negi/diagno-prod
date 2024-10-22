@extends('layouts.adminCommon')
@section('content')
<div class="container">
      <div class="row justify-content">
          <div class="col-md-12 pt-3">
            <div class="card">
            <div class="card-body">
                <form action="{{route('patient.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$patient->id ?? ''}}" />
                   <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{old('name', $patient->name ?? '')}}" required placeholder="Name" />
                            @error('name')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="number" class="form-control" value="{{ old('number', $patient->number ?? '')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                            .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  maxlength="12" placeholder="Number" required />
                            @error('number')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <!-- <div class="col-md-6 mt-3">
                            <label class="form-label">Emergency Number </label>
                            <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $patient->emergency_contact ?? '')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                            .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" minlength="10"  maxlength="12" placeholder="Emerygency Contact"/>
                            @error('emergency_contact')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div> -->
                        <!-- <div class="col-md-6 mt-3">
                            <label class="form-label">Insurance Number <span class="text-danger">*</span></label>
                            <input type="text" name="insurance_number" class="form-control" value="{{old('insurance_number', $patient->insurance_number ?? '')}}" required  placeholder="Insurance Number"/>
                            @error('insurance_number')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div> -->

                        <div class="col-md-6  mt-3">
                            <label class="form-label">Your Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email ?? '')}}" placeholder="Email" required />
                            @error('email')
                            <span class="validationclass text-danger" style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control" max="{{date('Y-m-d')}}"value="{{old('dob', $patient->dob ?? '')}}" placeholder="Date of Birth" required />
                            @error('dob')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <!-- <div class="col-md-6 mt-3">
                            <label class="form-label">Cedula</label>
                            <input type="text" name="cedula" class="form-control" value="{{old('cedula', $patient->cedula ?? '')}}" placeholder="Cedula" />
                            @error('cedula')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div> -->

                        {{-- <div class="col-md-6 pt-3 mt-3">
                            <label class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age"  class="form-control" value="{{ old('age', $patient->age ?? '')}}" min="0" maxlength="3"onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                           .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  required />
                            @error('age')
                            <span class="validationclass text-danger " style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div> --}}
                        {{-- <div class="col-md-6 pt-3 mt-3">
                            <label for="gender" class="form-label">Sex <span class="text-danger">*</span></label>
                            <select class="form-control form-select text-dark mr-1" name="sex" value="{{old('sex', $patient->sex ?? '')}}" >
                                <option value="Male" {{isset($patient) && $patient->sex=="Male" ? "selected":''}}>Male</option>
                                <option value="Female" {{isset($patient) && $patient->sex=="Female" ? "selected":''}}>Female</option>
                                <option value="Non binary" {{isset($patient) && $patient->sex=="Non binary" ? "selected":''}}>Non binary</option>
                            </select>
                            @error('sex')
                            <span class="validationclass text-danger" style="position: absolute;top: 105px;">{{$message}}</span>
                            @enderror
                        </div> --}}
                        <!-- <div class="col-md-6  mt-3">
                            <label class="form-label">Marital Status</label>
                            <select class="form-control form-select text-dark mr-1" name="marital_status" >
                                <option value="unmarried" {{isset($patient) && $patient->marital_status=="unmarried" ? "selected":''}}>Un-married
                                </option>
                                <option value="married" {{isset($patient) && $patient->marital_status=="married" ? "selected":''}}>Married
                                </option>
                            </select>
                        </div> -->
                        <div class="col-md-6  mt-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control form-select text-dark mr-1" name="status" value="{{old('status', $patient->status ?? '')}}">
                                <option value="1" {{isset($patient) && $patient->status=="1" ? "selected":''}}>Active
                                </option>
                                <option value="0" {{isset($patient) && $patient->status=="0" ? "selected":''}}>De-Active
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Address  <span class="text-danger">*</span></label>
                            <textarea type="text" name="address" class="form-control" placeholder="Address" required>{{old('address', $patient->address ?? '')}}</textarea>
                            @error('address')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                                 
                             <div class="col-lg-6">
                                <div class="form-group mb-3 {{ $errors->has('profile_image') ? 'has-error' : '' }}">
                                     <label class="form-label" for="name">profile image </label>
                                     <input type="file" id="profile_image" name="profile_image"  class="form-control" value="{{ old('profile_image', isset($patient) ? $patient->profile_image : '') }}" @if(!isset($patient)) required @endif>
                                     @if($errors->has('profile_image'))
                                         <p class="help-block">
                                             {{ $errors->first('profile_image') }}
                                         </p>
                                     @endif
                                 </div>
                                <div class="col-lg-6">
                                @if(isset($patient) && !empty($patient->profile_image))
                                    <img src="{{url('/uploads/patient',$patient->profile_image)}}" alt="" class="mt-2 mb-2" width="100" height="90">
                                    <input type="hidden" value="{{$patient->profile_image ?? ''}}" name="old_profile_image">
                                    @endif
                                </div>
                    
                    <a class="btn btn-warning btn-sm" href="{{ url()->previous() }}">Back</a>
                    <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
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
