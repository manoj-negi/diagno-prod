
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('lab-register.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$result->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                            <div class="col-md-6">  
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="name" value="{{ old('name', isset($updates) && isset($updates['name']) ? $updates['name'] : '') }}" name="name" class="form-control" required>
                            @error('name')
                                <div class="alert text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                                <div class="col-md-6 ">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" value="{{ old('email', isset($updates) && isset($updates['email']) ? $updates['email'] : '') }}" name="email"
                                    class="form-control" required>
                                    @error('email')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                           
                            </div>
                            </div>

                            <div class="row">
                            <div class="col-md-6">
                            <label class="form-label">Verified Number <span class="text-danger">*</span></label>
                            <input type="number" value="{{ old('number', isset($updates) && isset($updates['number']) ? $updates['number'] : '') }}" 
                                name="number" 
                                class="form-control" 
                                required>
                            @error('number')
                            <div class="alert text-danger">{{$message}}</div>
                            @enderror
                        </div>


                          
                            <div class="col-md-6 ">
                                <label class="form-label">Gst <span class="text-danger">*</span></label>
                                <input type="gst" value="{{ old('gst', isset($updates) && isset($updates['gst']) ? $updates['gst'] : '') }}" name="gst"
                                    class="form-control"required>
                                    @error('gst')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>

                            <div class="col-md-6 ">
                                <label class="form-label">Home Collection <span class="text-danger">*</span></label>
                                <select name="home_collection" class="form-control" id="">
                                    <option value="">Select</option>
                                    <option value="yes" {{ isset($updates) && isset($updates['home_collection']) ? $updates['home_collection'] : ''}}>YES</option>
                                    <option value="no"{{ isset($updates) && isset($updates['home_collection'])  ? $updates['home_collection'] : '' }}>NO</option>
                                </select>
                                <!-- <input type="checkbox" value="{{ old('home_collection', isset($updates) && isset($updates['home_collection']) ? $updates['home_collection'] : '') }}" name="home_collection">
                                    @error('gst')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror -->
                            </div>

                            <!-- PIN CODE -->
                            <div class="form-group col-6 mb-3 {{ $errors->has('postal_code') ? 'has-error' : '' }}">
                                <label class="form-label" for="postal_code">{{ __('Postal Code') }}<span class="text-danger">*</span></label>
                                <input id="model_tags" class="form-control" name="postal_code" value="{{ old('postal_code', isset($updates) && isset($updates['postal_code']) ? $updates['postal_code'] : '') }}" required/>
                                @if($errors->has('postal_code'))
                                    <p class="help-block">
                                        {{ $errors->first('postal_code') }}
                                    </p>
                                @endif
                            </div>

                            <!-- <div class="form-group col-6 mb-3 {{ $errors->has('model_tags') ? 'has-error' : '' }}">
                                <label class="form-label" for="model_tags">{{ __('Model Tags') }}</label>
                                <input id="model_tags" class="form-control" name="model_tags" value="{{ old('model_tags', isset($data->model->model_tags) ? $data->model->model_tags : '') }}" />
                                @if($errors->has('model_tags'))
                                    <p class="help-block">
                                        {{ $errors->first('model_tags') }}
                                    </p>
                                @endif
                            </div> -->
                            <!-- Pin code end -->

                            
                                <div class="col-md-6 mb-2">
                                <label class="form-label">Certification<span class="text-danger">*</span></label>
                                <input type="file" name="hospital_logo" value="{{$updates['hospital_logo'] ?? ''}}"
                                    class="form-control " />
                            <!-- </div>
                            <div class="col-lg-6"> -->
                                @if(isset($updates) && !empty($updates->hospital_logo))
                            <img src="{{url('uploads/lab-register',$updates->hospital_logo)}}" alt="" class="mt-2 mb-2" width="100" height="90">
                            <input type="hidden" value="{{$updates->hospital_logo ?? ''}}" name="old_hospital_logo" required>
                            @endif
                                </div>
                            </div>

                            
                           <div class="row">
                           <div class="form-group col-6 {{ $errors->has('state_id') ? 'has-error' : '' }}">
                            <label for="phone">State<span class="text-danger">*</span></label>
                            <select id="state_id" name="state_id" class="form-control" id="" required>
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
                        <select name="city[]" id="city" class="select2 form-control city" multiple="multiple" required>
                            @foreach($allCity ?? [] as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id,$city) ? 'selected' : '' }}>
                                    {{ $item->city }}
                                </option>
                            @endforeach
                            <!-- @foreach($allCity as $citys)
                                    <option value="{{ $citys }}">{{ $citys }}</option>
                                @endforeach -->
                        </select>
             @if($errors->has('city'))
                    <p class="help-block">
                        {{ $errors->first('city') }}
                    </p>
                @endif
            </div>
            </div>   
                          
                    <div class="row">
                          <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control">{{ old('address', isset($updates) && isset($updates['address']) ? $updates['address'] : '') }}</textarea>
                                 @error('address')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                         </div>
      
 
 
                <div class="row mt-3">
                    <div class="col-md-6">
   
                        <a href="{{route('lab-register.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div> 


<script>
        $(document).ready(function() {
            $('#state_id').change(function() {
                var companyId = $(this).val();
                $('#city').html('');
                console.log(companyId);
                $.ajax({
                    url: "{{ url('/get-city') }}",
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
