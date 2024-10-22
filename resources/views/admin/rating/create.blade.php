
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content ">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('ratingreviews.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}"> 
                        <div class="row mb-3">
    <div class="col-md-6">
        <label for="" class="form-label">Select hospital<span class="text-danger">*</span></label>
        <select name="hospital_id" class="mb-2 mr-sm-2 form-select" id="" required>
            <option value="">Select hospital</option> <!-- Added value attribute here -->
            @foreach($hospital as $key => $hospitalName)
                <option value="{{ $key }}" {{ isset($data) && $key == $data->hospital_id ? 'selected' : '' }}>
                    {{ $hospitalName }}
                </option>
            @endforeach
        </select>
    </div>


                       
    <div class="col-md-6">
        <label for="" class="form-label">Select Patient<span class="text-danger">*</span></label>
        <select name="patient_id" class="mb-2 mr-sm-2 form-select" id="" required>
            <option value="">Select Patient</option> 
            @foreach($patient as $key => $patients)
                <option value="{{$key}}" {{isset($data) && $key==$data->patient_id ? 'selected' : ''}}>{{$patients}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-md-6">
        <label class="form-label">Rating</label>
        <select name="ratings" class="mb-2 mr-sm-2 form-select" required>
            <option value="">Select Rating</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ isset($data) && $data->ratings == $i ? 'selected' : '' }}>
                    {{ $i }} star 
                </option>
            @endfor
        </select>
    </div>
</div>



<div class="row">
    <div class="form-group">
        <label class="form-label">Review<span class="text-danger">*</span></label>
        <textarea name="review" class="form-control" rows="2" placeholder="Enter your review here" required>{{ old('review', isset($data) && isset($data->review) ? $data->review : '') }}</textarea>
        @error('review')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>


                        
                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm mt-3">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
                      </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
