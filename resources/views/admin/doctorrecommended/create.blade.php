@extends('layouts.adminCommon')
@section('content')
<div class="container mt-3">
    <div class="row justify-content">
        <div class="col-md-12 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('doctor-recommended.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}" />
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Doctor Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{old('name', $data->name ?? '')}}" required />
                                @error('name')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                                @enderror
                            </div>
                        
                            <div class="col-md-6 ">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" name="designation" class="form-control" value="{{ old('designation', $data->designation ?? '')}}" required />
                                @error('designation')
                                <span class="validationclass text-danger" style="position: absolute;
                                top: 105px;">{{$message}}</span>
                                @enderror
                            </div>
                        
                            {{-- <div class="col-md-6">
                                <label class="form-label">Rating</label>
                                <select name="rating" id="" class="form-select">
                                    <option value="1" {{isset($data) && $data->ratings=="1"?'selected':''}}>1 star</option>
                                    <option value="2" {{isset($data) && $data->ratings=="2"?'selected':''}}>2 star</option>
                                    <option value="3" {{isset($data) && $data->ratings=="3"?'selected':''}}>3 star</option>
                                    <option value="4" {{isset($data) && $data->ratings=="4"?'selected':''}}>4 star</option>
                                    <option value="5" {{isset($data) && $data->ratings=="5"?'selected':''}}>5 star</option>
                                </select>
                                @error('rating')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                                @enderror
                            </div> --}}
                            <div class="col-md-6">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control"/>
                                @if(isset($data) && !empty($data->image))
                            <img class="img-fluid rounded my-4" src="{{url('uploads/testimonial/',$data->image ?? '')}}" height="70px" width="60px" alt="User avatar" >
                            @endif
                            @error('image')
                            <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                            @enderror
                            </div>
                        
                            <div class="col-md-6 ">
                                <label class="form-label mt-3">Status</label>
                                <select class="form-control form-select text-dark mr-1" name="status" value="{{old('status', $data->status ?? '')}}">
                                    <option value="1" {{isset($data) && $data->status=="1" ? "selected":''}}>Active
                                    </option>
                                    <option value="0" {{isset($data) && $data->status=="0" ? "selected":''}}>De-Active
                                    </option>
                                </select>
                                @error('status')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mt-3">Review</label>
                                <textarea  name="review" class="form-control" row="3" placeholder="review" required>{{ old('review', isset($data) && isset($data->review) ? $data->review : '') }}</textarea>
                                @error('review')
                                <span class="text-danger validationclass" role="alert">
                                <strong>{{$message}}</strong>
                                </span>
                                    @enderror
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
