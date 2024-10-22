@extends('layouts.adminCommon')
@section('content')
<div class="container">
<div class="row">
        <div class="col-md-12 text-right">
    </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
        <div class="card body ">
            <div class="col-md-12">
                <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$edit->id ?? ''}}" />
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label  class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{old('name',$edit->name ?? '')}}" required/>
                            @error('name')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label  class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{old('email', $edit->email ?? '')}}" required />
                            @error('email')
                                <span class="text-danger validationclass" role="alert">
                                 <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of birth</label>
                            <input type="date" name="dob" class="form-control" value="{{old('dob', $edit->dob ??'')}}" />
                            @error('dob')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="col-md-6  mb-3">
                            <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" name="number" class="form-control"
                                value="{{old('number',$edit->number ?? '')}}" min="0" maxlength="10" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                                .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  required/>
                                @error('number')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                        </div>
                    </div>   
                        <div class="row">
                              <div class="col-md-6 mb-3 form-group">
                                <label class="form-label">Role<span class="text-danger">*</span></label>
                                <select name="role" class="form-select mb-2  mr-sm-2 " required>
                                  <option>Select Role</option>
                                    @foreach($role as $key => $value)
                                   <option value="{{$key}}"{{isset($edit) && $edit->roles[0]->id==$key?'selected':''}}>{{ucfirst($value)}}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="col-md-6 form-group ">
                        <label class="form-label">status</label>
                        <select name="status" class="form-control">
                        <option value="0" {{isset($edit) && $edit->status=="0"?"selected" : ''}}>Active</option>  
                          <option value="1" {{isset($edit) && $edit->status=="1"?"selected" : ''}}>De-Active</option>
                      </select>
                    </div>
                </div>
                     <div class="row">
                        <div class="col-md-6  mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control">{{old('address', $edit->address ?? '')}}</textarea>
                            @error('address')
                                    <span class="text-danger validationclass" role="alert">
                                        <strong>{{$message}}</strong>
                                    </span>
                                @enderror
                        </div>                        
                    </div>                                            
            <div class="col-md-4">
                 <a href="{{route('users.index')}}" class="btn btn-warning btn-sm my-2">Back</a>
                <button type="submit" class="btn btn-primary btn-sm mb-0" value="submit my-2">Submit</button>
            </div>
            </form>

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