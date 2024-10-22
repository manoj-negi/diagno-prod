@extends('layouts.adminCommon')
@section('content')
<div class="container">
   <div class="row justify-content">
      <div class="col-12 pt-5 ">
         <div class="card">
            <div class="card-body">
               @if ($message = Session::get('msg'))
               <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
                  <button type="button" class="close" data-dismiss="alert">×</button> 
                  <strong>{{ $message }}</strong>
               </div>
               @endif
               <form action="{{route('lab.store')}}" method="post" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="id" value="{{$id ?? ''}}">
                  <!-- <div class="row"> -->
                  <div class="row">
                     <div class="col-md-6">
                        <label class="form-label">Name*</label>
                        <input type="text" value="{{ old('name', isset($updates) && isset($updates['name']) ? $updates['name'] : '') }}" name="name" class="form-control" required>
                        @error('name')
                        <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                     </div>
                     <div class="col-md-6 ">
                        <label class="form-label">Email*</span></label>
                        <input type="email" value="{{ old('email', isset($updates) && isset($updates['email']) ? $updates['email'] : '') }}" name="email"
                           class="form-control" required>
                        @error('email')
                        <div class="alert text-danger">{{$message}}</div>
                        @enderror
                     </div>
                     @if(Auth::user()->roles->contains(1))
                     <div class="col-md-6 ">
                        <label class="form-label">password*</span></label>
                        <input type="password"  name="password"
                           class="form-control">
                        @error('password')
                        <div class="alert text-danger">{{$message}}</div>
                        @enderror
                     </div>
                     @else(Auth::user()->roles->contains(4))
                     @endif
                     <!-- </div> -->
                     <!-- </div> -->
                     <!-- <div class="row"> -->
                     <div class="col-md-6">
                        <label class="form-label">Verified Number<span class="text-danger">*</span></label>
                        <input type="number" value="{{ old('number', isset($updates) && isset($updates['number']) ? $updates['number'] : '') }}" 
                           name="number" 
                           class="form-control" 
                           >
                        @error('number')
                        <div class="alert text-danger">{{$message}}</div>
                        @enderror
                     </div>
                     <div class="col-md-6 ">
                        <label class="form-label">Gst*</span></label>
                        <input type="gst" value="{{ old('gst', isset($updates) && isset($updates['gst']) ? $updates['gst'] : '') }}" name="gst"
                           class="form-control">
                        @error('gst')
                        <div class="alert text-danger">{{$message}}</div>
                        @enderror
                     </div>
                     <!-- PIN CODE -->
                     <div class="form-group col-6 mb-3 {{ $errors->has('postal_code') ? 'has-error' : '' }}">
                        <label class="form-label" for="postal_code">{{ __('Postal Code') }}<span class="text-danger">*</span></label>
                        <input id="model_tags" class="form-control" name="postal_code" value="{{ old('postal_code', isset($updates) && isset($updates['postal_code']) ? $updates['postal_code'] : '') }}" />
                        @if($errors->has('postal_code'))
                        <p class="help-block">
                           {{ $errors->first('postal_code') }}
                        </p>
                        @endif
                     </div>
                     <!-- Pin code end -->
                     <div class="col-md-6 mb-2">
                        <label class="form-label">Lab Logo<span class="text-danger">*</span></label>
                        <input type="file" name="hospital_logo" value=""
                           class="form-control " />
                        <!-- </div>
                           <div class="col-lg-6"> -->
                        @if(isset($updates) && !empty($updates->hospital_logo))
                        <img src="{{url('uploads/hospital',$updates->hospital_logo)}}" alt="" class="mt-2 mb-2" width="100" height="90">
                        <input type="hidden" value="{{$updates->hospital_logo ?? ''}}" name="old_hospital_logo" >
                        @endif
                     </div>
                     <!-- home start collection -->
                     <div class="col-md-6 ">
                        <div class="d-flex align-items-center">
                           <div class="lb mt-3">
                              <label class="form-label">Home Collection<span class="text-danger">*</span></label>
                           </div>
                           <div class="in">
                              <input class="form-check-input ms-2 mt-0" type="checkbox" name="home_collection" {{ isset($updates) && $updates->home_collection==true ? 'checked' : '' }} value="1">
                           </div>
                        </div>
                     </div>
                     <!-- home end collection -->
                  </div>
                  <div class="row">
                     <div class="form-group col-6 {{ $errors->has('state_id') ? 'has-error' : '' }}">
                        <label for="phone">State<span class="text-danger">*</span></label>
                        <select id="state_id1" name="state_id" class="form-control" id="" >
                        @foreach($state as $stateItem)
                        <option value="{{ $stateItem->id }}" {{ old('state_id', isset($updates) && $updates->state_id == $stateItem->id) ? 'selected' : '' }}>
                        {{ $stateItem->name }}        
                        </option>
                        @endforeach
                        </select>
                        @if($errors->has('state_id'))
                        <p class="help-block">
                           {{ $errors->first('state_id') }}
                        </p>
                        @endif
                     </div>
                     <div class="form-group col-6 mb-3 {{ $errors->has('city') ? 'has-error' : '' }}">
                        <label class="form-label" for="city">{{ __('City') }}<span class="text-danger">*</span></label>
                        <select name="city[]" id="city" class="select2 form-control city" multiple="multiple">
                        @foreach($city ?? [] as $item)
                        <option value="{{ $item->id }}" {{ isset($updates->cities) && in_array($item->id,$updates->cities->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $item->city }}
                        </option>
                        @endforeach
                        </select>
                        @if($errors->has('city'))
                        <p class="help-block">
                           {{ $errors->first('city') }}
                        </p>
                        @endif
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-md-6 {{ $errors->has('hospital_category') ? 'has-error' : '' }}">
                        <label for="phone">Lab Category<span class="text-danger">*</span></label>
                        <select id="hospital_category1" name="hospital_category" class="form-control" id="" >
                           <option value="">Select Lab Category</option>
                           @foreach($HospitalCategory as $item)
                           <option value="{{ $item->id }}" {{ old('hospital_category', isset($updates) && $updates->hospital_category == $item->id) ? 'selected' : '' }}>
                           {{ $item->title }}        
                           </option>
                           @endforeach
                        </select>
                        @if($errors->has('hospital_category'))
                        <p class="help-block">
                           {{ $errors->first('hospital_category') }}
                        </p>
                        @endif
                     </div>
                     <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control">{{ old('address', isset($updates) && isset($updates['address']) ? $updates['address'] : '') }}</textarea>
                        @error('address')
                        <div class="alert text-danger">{{$message}}</div>
                        @enderror
                     </div>
                     <div class="col-md-12">
                        <label class="form-label">About lab</label>
                        <textarea name="hospital_description" class="form-control">{{ old('hospital_description', isset($updates) && isset($updates['hospital_description']) ? $updates['hospital_description'] : '') }}</textarea>
                        @error('hospital_description')
                        <div class="alert text-danger">{{$message}}</div>
                        @enderror
                     </div>
                  </div>
                  <div class="row mt-4">
                     <hr>
                     <span class="mt-2 mb-3">Lab Availability</span>
                     <div class="col-md-12">
                        @if(isset($updates) && (count($updates->labAvailability)>0))
                        @foreach($updates->labAvailability as $value)
                        <input type="hidden" name="vendor_available_id[]" value="{{ $value->id }}">
                        <div class="row">
                           <div class="form-group col-md-3">
                              <div class="form-check">
                                 <label class="form-check-label text-muted">
                                 <input class="form-check-input " type="checkbox" name="weekday[{{$value->week_day}}]" {{ $value->status == 1 ? 'checked' : '' }} value="1"> {{ $week_arr[$value->week_day] }}
                                 </label>
                              </div>
                           </div>
                           <div class="form-group col-md-3">
                              <input type="time" name="start_time[{{$value->week_day}}]" class="form-control" value="{{ $value->start_time }}">
                           </div>
                           <div class="form-group col-md-3">
                              <input type="time" name="end_time[{{$value->week_day}}]" class="form-control" value="{{ $value->end_time }}">
                           </div>
                        </div>
                        @endforeach
                        @else
                        @for($i=1; $i<=7; $i++)
                        <div class="row">
                           <div class="form-group col-md-3">
                              <div class="form-check">
                                 <label class="form-check-label text-muted">
                                 <input class="form-check-input " type="checkbox" name="weekday[{{$i}}]" {{ old('weekday[$i]') ? 'checked' : '' }} value="1"> {{ $week_arr[$i] }}
                                 </label>
                              </div>
                           </div>
                           <div class="form-group col-md-3">
                              <input type="time" name="start_time[{{$i}}]" class="form-control" value="09:00">
                           </div>
                           <div class="form-group col-md-3">
                              <input type="time" name="end_time[{{$i}}]" class="form-control" value="17:00">
                           </div>
                        </div>
                        @endfor
                        @endif
                     </div>
                  </div>
                  <div class="row mt-3">
                     <div class="col-md-6">
                        <a href="{{route('lab.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
               </form>
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   @if(isset($updates) && Auth::user()->roles->contains(4))
   <div class="card">
      <div class="card-body">
         <div class="row">
            <form method="POST" action="{{ route('change.password') }}">
               @csrf
               <input type="hidden" name="id" value="{{$id ?? ''}}">
               <div class="row">
                  <div class="col-md-6">
                     <label for="old_password">Old Password</label>
                     <input id="old_password" type="password" name="old_password" class="form-control" >
                     @error('msg')
                     <div class="alert text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6">
                     <label for="new_password">New Password</label>
                     <input id="new_password" type="password" name="new_pasword" class="form-control" >
                     @error('new_pasword')
                     <div class="alert text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6">
                     <label for="new_password_confirmation">Confirm New Password</label>
                     <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control" >
                     @error('new_password_confirmation')
                     <div class="alert text-danger">{{$message}}</div>
                     @enderror
                  </div>
               </div>
               <button type="submit" value="submit"  class="btn btn-primary btn-sm mt-3">Change Password</button>
            </form>
         </div>
      </div>
   </div>
   @endif
</div>
<script>
   $(document).ready(function() {
       $('#state_id').change(function() {
           var companyId = $(this).val();
           $('#city').html('');
           console.log(companyId);
           $.ajax({
               url: "{{ url('/get-citys') }}",
               method: 'GET',
               data: {
                   state_id: companyId
               },
               success: function(data) {
                   $('#city').html(data.outputDataemployees);
               },
               error: function(xhr, status, error) {
                   console.error(xhr.responseText);
               }
           });
       });
   });
</script>
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
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
   ClassicEditor
       .create(document.querySelector('#hospital_description'))
       .catch(error => {
           console.error(error);
       });
</script>
@endsection