@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
    
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('offer.update', ['offer' => $offer->offer_id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $offer->offer_id }}" />

                <div class="row">
                    <div class="col-6">
                        <label>Title</label>
                        <input type="text" name="title" value="{{ old('title', $offer->title) }}" class="form-control" />
                        @error('title')
                        <div class="validationclass text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 mt-2">
                        <label>Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($offer->start_date)->format('Y-m-d')) }}" class="form-control" />
                        @error('start_date')
                        <div class="validationclass text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 mt-2">
                        <label>End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($offer->end_date)->format('Y-m-d')) }}" class="form-control" />
                        @error('end_date')
                        <div class="validationclass text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 mt-2">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $offer->description) }}</textarea>
                        @error('description')
                        <div class="validationclass text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Add other fields here -->

                </div>

                <button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">Update</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.24.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
    CKEDITOR.config.allowedContent = true;
</script>

@endsection
