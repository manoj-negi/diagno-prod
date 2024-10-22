@extends('layouts.adminCommon')
@section('content')
<div class="container mt-5">
      <div class="row justify-content">
          <div class="col-md-12 ">
            <div class="card">
            <div class="card-body">
                <form action="{{route('appointments-bills.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$data->id ?? ''}}" />
                   <div class="row">
                   <div class="col-md-6">
                            <label class="form-label">appointment<span class="text-danger">*</span></label>
                            <select name="appointment_id" class="form-control"required>                                   
                                  <option value="">appointment selected</option>
                                    @foreach($hospital_appointment as $key => $val)
                                    <option value="{{$val->id}}" {{isset($data) && $val->id == $data->appointment_id ? 'selected' : ''}}>#{{$val->patient->name}}</option>
                                    @endforeach
                                </select>
                            @error('appointment_id')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                       
                        <div class="col-md-6">
                            <label class="form-label">Title<span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{old('title', $data->title ?? '')}}" required />
                            @error('title')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                      
                        <div class="col-md-6 ">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number"  min="0" name="amount" class="form-control" value="{{ old('amount', $data->amount ?? '')}}" required />
                            @error('amount')
                            <span class="validationclass text-danger" style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                     
                        
                        <div class="col-lg-6">
           <div class="form-group mb-3 {{ $errors->has('document_file') ? 'has-error' : '' }}">
                <label class="form-label" for="name">document file </label>
                <input type="file" id="document_file" name="document_file"  class="form-control" value="{{ old('document_file', isset($data) ? $data->document_file : '') }}" @if(!isset($data))  @endif>
                @if($errors->has('document_file'))
                    <p class="help-block">
                        {{ $errors->first('document_file') }}
                    </p>
                @endif
            </div>
           </div>
           <div class="col-lg-6">
            @if(isset($data) && !empty($data->document_file))
          
            <input type="hidden" value="{{$data->document_file ?? ''}}" name="old_document_file">
            @endif
</div>

           
                           
                        
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
