@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('radiology.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{isset($data)?$data->id:''}}" name="id">
                              <input type="hidden" name="url" value="{{ app('request')->input('url') }}">
                          
                           <div class="col-md-6">
                            <label class="form-label">Test Name<span class="text-danger">*</span></label>
                            <input type="text" name="test_name" class="form-control" value="{{ old('test_name', $data->package_name ?? '') }}" required />
                            @error('test_name')
                                <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{ $message }}</span>
                            @enderror
                        </div>
                   
                        <div class="col-md-6">
                            <label class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" value="{{ old('amount', $data->amount ?? '') }}" required />
                            @error('amount')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(Auth::user()->roles->contains(1))
                        <div class="col-md-6">
                            <label class="form-label">Lab<span class="text-danger">*</span></label>
                            <select name="lab_id" class="form-control" required>
                                <option value="1">Diagno Mitra</option>
                                @foreach($labs as $item)
                                <option value="{{$item->id}}" {{isset($data) && $data->lab_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('amount')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                        <div class="form-group col-12 mb-3 {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label class="form-label" for="description">{{ __('Description') }}</label>
                            <textarea type="text" id="ckeditor" name="description"  class="form-control ckeditor" value="" >{{ old('description', isset($data) ? $data->description : '') }} </textarea>
                            @if($errors->has('description'))
                                <p class="help-block">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                        </div>
                           
                        <div class="mt-3 mb-3">
                        <a href="{{ route('radiology.index') }}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content');
CKEDITOR.config.allowedContent = true;
</script>
