@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('faqs.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$result->id ?? ''}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Question<span class="text-danger">*</span></label>

                                    <textarea name="question" class="form-control" placeholder="question" required>{{ old('question', isset($result) && isset($result->question) ? $result->question : '') }}</textarea>
                                </div>
                                    @error('question')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Answer<span class="text-danger">*</span></label>
                                    <textarea name="answer" class="form-control" placeholder="Answer" required>{{ old('answer', isset($result) && isset($result->answer) ? $result->answer : '') }}</textarea>
                                    </div>
                                    @error('answer')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                            </div>
                        <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control form-select text-muted mr-1">
                            <option value="1" {{isset($result) && $result->status=="1"?'selected':''}}>Active </option>
                            <option value="0" {{isset($result) && $result->status=="0"?'selected':''}}>De-Active</option>
                        </select>
                        </div>
                        </div>
                    

                        <div class="mb-3">
                        <a href="{{ route('faqs.index') }}" class="btn btn-warning btn-sm">Back</a>
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
