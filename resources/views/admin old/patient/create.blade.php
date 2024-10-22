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
                            <input type="text" name="name" class="form-control" value="{{old('name', $patient->name ?? '')}}" required />
                            @error('name')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="number" class="form-control" value="{{ old('number', $patient->number ?? '')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                            .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  maxlength="10" required />
                            @error('number')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 pt-3 mt-3">
                            <label class="form-label">Your Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email ?? '')}}" required />
                            @error('email')
                            <span class="validationclass text-danger" style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 pt-3 mt-3">
                            <label class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age"  class="form-control" value="{{ old('age', $patient->age ?? '')}}" min="0" maxlength="3"onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                           .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  required />
                            @error('age')
                            <span class="validationclass text-danger " style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 pt-3 mt-3">
                            <label for="gender" class="form-label">Sex <span class="text-danger">*</span></label>
                            <select class="form-control form-select text-dark mr-1" name="sex" value="{{old('sex', $patient->sex ?? '')}}" >
                                <option value="Male" {{isset($patient) && $patient->sex=="Male" ? "selected":''}}>Male</option>
                                <option value="Female" {{isset($patient) && $patient->sex=="Female" ? "selected":''}}>Female</option>
                                <option value="Non binary" {{isset($patient) && $patient->sex=="Non binary" ? "selected":''}}>Non binary</option>
                            </select>
                            @error('sex')
                            <span class="validationclass text-danger" style="position: absolute;top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 pt-3 mt-3">
                            <label class="form-label">Status</label>
                            <select class="form-control form-select text-dark mr-1" name="status" value="{{old('status', $patient->status ?? '')}}">
                                <option value="1" {{isset($patient) && $patient->status=="1" ? "selected":''}}>Active
                                </option>
                                <option value="0" {{isset($patient) && $patient->status=="0" ? "selected":''}}>De-Active
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 pt-3 mb-3 mt-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control"/>
                        </div>
                        <div class="col-md-6 mb-3 pt-3 mt-3">
                        @if(isset($patient) && !empty($patient->profile_image))
                        <img class="img-fluid rounded my-4" src="{{url('uploads/profile-imges',$patient->profile_image ?? '')}}" height="70px" width="60px" alt="User avatar" >
                        @endif
                        </div>
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
