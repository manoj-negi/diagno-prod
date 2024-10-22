@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('hospitals.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{Auth::user()->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" placeholder="Name" required/>
                                    </div>
                                    @error('name')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Email<span class="text-danger">*</span></label>
                                        <input type="text" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" placeholder="Email" />
                                    </div>
                                    @error('email')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Password<span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control"  placeholder="password" />
                                    </div>
                                    @error('email')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>

                              <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="" class="form-label">Hospital Category<span class="text-danger">* </span></label>
                                        <select name="hospital_category"  class="form-select"
                                           required>
                                           @foreach($HospitalCategory as  $key=>$item)
                                            <option value="{{$item->id}}" {{isset($result) && $result->hospital_category == $item->id ? 'selected' : ''}}>{{$item->title}}</option>
                                         
                                            @endforeach
                                        </select>
                                          @error('hospital_category')
                                        <span class="validationclass text-danger pt-4">{{$message}}</span>
                                        @enderror 
                                    </div>
                                 
                               
                            </div>
                            </div>
                            
                                   
      </div>
                            <div class="row">
                            <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="" class="form-label">State<span class="text-danger">*</span></label>
                                    <select name="state_id" id="state_id" class="form-select" 
                                       required>
                                       @foreach($State as  $key=>$item)
                                        <option value="{{$item->id}}" {{isset($result) && $result->state_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                     
                                        @endforeach
                                        <!-- <option value="1" {{isset($result) && $result->state_id == "1" ? 'selected' : ''}}>Kota</option>
                                        <option value="2" {{isset($result) && $result->state_id == "2" ? 'selected' : ''}}>Jaipur</option> -->
                                    </select>
                                      @error('state_id')
                                    <span class="validationclass text-danger pt-4">{{$message}}</span>
                                    @enderror 
                                </div>
                               </div>
                            </div>
                            <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="" class="form-label">City<span class="text-danger">*</span></label>
                                    <select name="city_id"  id="city-data" class="form-select" 
                                       required>
                                       @foreach($city ?? [] as $item)
                                       <option value="{{$item->id}}" {{isset($result) && $result->city_id == $item->id ? 'selected' : ''}}>{{$item->city}}</option>
                                       @endforeach
                                    </select>
                                      @error('city_id')
                                    <span class="validationclass text-danger pt-4">{{$message}}</span>
                                    @enderror 
                                </div>
                             </div>
                            </div>
                            
                          </div>
                          </div>
                       
<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label" for="basic-default-fullname">Address<span class="text-danger">*</span></label>
            <textarea  name="address" class="form-control">{{ old('address',Auth::user()->address)}}</textarea>
        </div>
        @error('address')
        <div class="validationclass text-danger pt-2">{{$message}}</div>
        @enderror
    </div>
</div>
                        <a href="{{route('hospitals.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                      </form>
                </div>
        </div>
    </div>
<script>

$("#state_id").change(function(){
     var state_id = this.value;

     $.ajax({
                  type: 'GET',
                  url: '/state',
                  data: {state_id:state_id},
                  success: function(data){
                     $("#city-data").html(data.outputData);
                  }
               });
   
  });

    </script>
@endsection
