@extends('layouts.adminCommon')
@section('content')
<div class="container">
      <div class="row justify-content">
          <div class="col-md-12 pt-5">
            <div class="card">
            <div class="card-body">
                <form action="{{route('lab-category.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$data->id ?? ''}}" />
                   <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Title<span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{old('title', $data->title ?? '')}}" required />
                            @error('title')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                        </div>
                      
                        <div class="col-md-6 ">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control" value="{{ old('description', $data->description ?? '')}}" required />
                            @error('description')
                            <span class="validationclass text-danger" style="position: absolute;
                            top: 105px;">{{$message}}</span>
                            @enderror
                        </div>
                     
                        
                          <div class="col-md-6">
                            <label for="image" class="form-label">Image<span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" required/>
                           
                         @error('image')
                        <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                         @enderror
                        </div>
                        <div class="col-md-6">
                        @if(isset($data) && !empty($data->image))
                        <img class="img-fluid rounded my-4" src="{{url('uploads/testimonial',$data->image ?? '')}}" height="80px" width="60px" alt="User avatar" >
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
