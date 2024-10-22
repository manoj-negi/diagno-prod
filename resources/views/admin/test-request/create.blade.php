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
                    <form action="{{route('test-request.store')}}" method="post" enctype="multipart/form-result">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{isset($result)?$result->id:''}}" name="id">
                           <!-- <div class="col-lg-6">
                           <div class="form-group mb-3 {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label class="form-label" for="name">Lab<span class="text-danger">*</span></label>
                                <select name="hospital_id" class="form-control" required>                                   
                                  <option value="">lab Selected</option>
                                    @foreach($abc as $key => $val)
                                    <option value="{{$val->id}}" {{isset($result) && $val->id==$result->hospital_id ? 'selected' : ''}}>{{$val->name ?? $val->email}}</option>
                                    @endforeach
                                </select>
                                 @if($errors->has('hospital_id'))
                                    <p class="help-block">
                                        {{ $errors->first('hospital_id') }}
                                    </p>
                                @endif
                            </div>
                           </div> -->
                           <div class="col-md-6">
                            <label class="form-label">Patient Name<span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control" value="{{ request()->get('name') }}" required />
                            @error('patient_name')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{ $message }}</span>
                            @enderror
                        </div>
<!-- Lab Selection Dropdown -->
@if(Auth::user()->roles->contains(1)) 
    <div class="col-md-6">
        <label for="lab_id" class="form-label">Select Lab<span class="text-danger">*</span></label>
        <select name="lab_id" class="form-control labId" id="lab_id" required>
            <option value="">Select Lab</option>
            @foreach($labs as $lab)
                <option value="{{ $lab->id }}" {{ old('lab_id', $result->lab_id ?? '') == $lab->id ? 'selected' : '' }}>
                    {{ $lab->name }}
                </option>
            @endforeach
        </select>
        @error('lab_id')
        <span class="validationclass text-danger">{{ $message }}</span>
        @enderror
    </div>
@else
    @if(Auth::user()->roles->contains(4)) 
        <input type="hidden" name="lab_id" value="{{ Auth::user()->lab_id }}" />
    @endif
@endif
                        <div class="col-md-6">
                            <label class="form-label">Patient Age<span class="text-danger">*</span></label>
                            <input type="number" max="100" min="0" name="age" class="form-control" value="{{ request()->get('age') }}" required />
                            @error('age')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                    <label class="form-label">Patient Gender<span class="text-danger">*</span></label>
                    <select class="form-control" name="gender" id="" required>
                        <option value="">Select Gender</option>
                        <option value="Male" @if(request()->get('gender') == 'Male') selected @endif>Male</option>
                        <option value="Female" @if(request()->get('gender') == 'Female') selected @endif>Female</option>
                        <option value="Other" @if(request()->get('gender') == 'Other') selected @endif>Other</option>
                    </select>
                    <!-- <input type="number" name="age" class="form-control" value="{{old('age', $result->age ?? '')}}" required /> -->
                    @error('gender')
                    <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                    @enderror
                </div>


                        <div class="col-md-6">
                            <label class="form-label">Patient Contact Number<span class="text-danger">*</span></label>
                            <input type="number" name="contact" class="form-control" value="{{ request()->get('number') }}" required />
                            @error('contact')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Patient Full Address<span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ request()->get('address') }}" required />
                            @error('address')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Test <span class="text-danger">*</span></label>
                            <input type="text" name="test_type" class="form-control" value="{{ request()->get('test') }}" required />
                            @error('test_type')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>

                        <!-- <div class="row"> -->
                        <div class="col-md-6">
                            <label class="form-label">Payment Status<span class="text-danger">*</span></label>
                            <select class="form-control" name="payment_status" id="">
                                <option value="pre">Pre</option>
                                <option value="post">Post</option>
                            </select>
                            <!-- <input type="text" name="payment_status" class="form-control" value="{{old('payment_status', $result->payment_status ?? '')}}" required /> -->
                            @error('payment_status')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="number"  min="0" name="amount" class="form-control" value="{{ request()->get('amount') }}" required />
                            @error('amount')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                        <!-- </div> -->
                        


                        <!-- <div class="form-group col-12 mb-3 {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label class="form-label" for="description">{{ __('Inner Test') }}</label>
                            <textarea type="text" id="ckeditor" name="description"  class="form-control ckeditor" value="" >{{ old('description', isset($result) ? $result->description : '') }} </textarea>
                            @if($errors->has('description'))
                                <p class="help-block">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div> -->
                      
                        <!-- @can('dashboard_support_list')
                        <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="admin_status" class="form-control form-select text-muted mr-1">
                            <option value="pending" {{isset($result) && $result->admin_status=="pending"?'selected':''}}>Pending </option>
                            <option value="approved" {{isset($result) && $result->admin_status=="approved"?'selected':''}}>Approved</option>
                            <option value="rejected" {{isset($result) && $result->admin_status=="rejected"?'selected':''}}>rejected</option>
                        </select>
                        </div>
                        @endcan -->

                @can('dashboard_labs_test_list')
                <div class="col-md-6">
                        <div class="mb-3">
                        <!-- <label class="form-label">Status</label> -->
                        <!-- <select type="hidden" name="admin_status" class="form-control form-select text-muted mr-1">
                            <option value="pending" {{isset($result) && $result->admin_status=="pending"?'selected':''}}>Pending </option>
                            <option value="approved" {{isset($result) && $result->admin_status=="approved"?'selected':''}}>Approved</option>
                            <option value="rejected" {{isset($result) && $result->admin_status=="rejected"?'selected':''}}>rejected</option>
                        </select> -->
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
