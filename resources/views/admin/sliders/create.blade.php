@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('sliders.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-fullname">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', isset($data) && isset($data->title) ? $data->title : '') }}" placeholder="title" required/>
                                    </div>
                                    @error('title')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                                </div>
                            

                          
                          
               <div class="col-lg-6">
                                <div class="form-group mb-3 {{ $errors->has('image') ? 'has-error' : '' }}">
                                     <label class="form-label" for="name">Image <span class="text-danger">*</span></label>
                                     <input type="file" id="image" name="image"  class="form-control" value="{{ old('image', isset($data) ? $data->image : '') }}" @if(!isset($data)) required @endif>
                                     @if($errors->has('image'))
                                         <p class="help-block">
                                             {{ $errors->first('image') }}
                                         </p>
                                     @endif
                                 </div>
                                <div class="col-lg-6">
                                @if(isset($data) && !empty($data->image))
    <img src="{{url('uploads/sliders',$data->image)}}" alt="" class="mt-2 mb-2" width="100" height="90">
    <input type="hidden" value="{{$data->image ?? ''}}" name="old_image">
    @endif
                                </div>
                                </div>
    
<div class="row">
    <div class="col-md-6">
   
                        <a href="{{route('sliders.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                      </form>
                </div>
            </div>
        </div>
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
