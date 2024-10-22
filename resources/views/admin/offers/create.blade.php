@extends('layouts.adminCommon')
@section('content')
<div class="container"></br>
<div class="row">
@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
   <div class="col-md-12 text-right">
    <a href="{{route('offers.index')}}" class="btn btn-primary btn-sm">Back</a></br></br>
</div>
</div>
    <div class="row">
        <div class="card"></br>
            <div class="col-md-12">
            <form method="POST" action="{{ route('offers.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ old('id', $data->id ?? '') }}" />

        <div class="row">
            <div class="col-6">
                <label>Offer Name</label>
                <input type="text" name="offer_name" value="{{ old('offer_name', $data->offer_name ?? '') }}" class="form-control" />
                @error('offer_name')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6">
                <label>image</label>
                <input type="file" name="image" class="form-control" />
                @error('image')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 mt-2">
                <label>Offer Start Date</label>
                <input type="date" name="validity" value="{{ old('validity', isset($data->validity) ? \Carbon\Carbon::parse($data->validity)->format('Y-m-d') : '') }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control" />
                @error('validity')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            @if(isset($data) && !empty($data->image))
            <div class="col-md-6">
                    <img class="img-fluid rounded my-4"
                    src="{{url('uploads/package',$data->image ?? '')}}" height="80px" width="160px"
                    alt="User avatar">
                </div>
                    @endif

                    <div class="col-6 mt-2">
                        <label>Offer End Date</label>
                        <input type="date" name="validity_end" value="{{ old('validity_end', isset($data->validity_end) ? \Carbon\Carbon::parse($data->validity_end)->format('Y-m-d') : '') }}" class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                        @error('validity_end')
                        <div class="validationclass text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-6 mt-2">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $data->description ?? '') }}</textarea>
                        @error('description')
                        <div class="validationclass text-danger">{{ $message }}</div>
                        @enderror
                    </div>
        </div>

        <button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">Submit</button>
    </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
        CKEDITOR.replace( 'banner_section' );
        CKEDITOR.replace( 'content' );
        CKEDITOR.config.allowedContent = true;
</script>

@endsection