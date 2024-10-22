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
                                    <label class="form-label">Question:</label>
                                    <input type="text" name="question" value="{{ old('question', isset($result) && isset($result->question) ? $result->question : '') }}"  placeholder="Question" class="form-control">
                                    </div>
                                    @error('question')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Spenish Question:</label>
                                    <input type="text" name="spenish_question" value="{{ old('spenish_question', isset($result) && isset($result->spenish_question) ? $result->spenish_question : '') }}"  placeholder="Question" class="form-control">
                                    </div>
                                    @error('spenish_question')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Answer</label>
                                    <textarea name="answer" class="form-control" placeholder="Answer">{{ old('answer', isset($result) && isset($result->answer) ? $result->answer : '') }}</textarea>
                                    </div>
                                    @error('answer')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Spenish Answer</label>
                                    <textarea name="spenish_answer" class="form-control" placeholder="Answer">{{ old('spenish_answer', isset($result) && isset($result->spenish_answer) ? $result->spenish_answer : '') }}</textarea>
                                    </div>
                                    @error('spenish_answer')
                                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                                    @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control form-select text-muted mr-1">
                            <option value="1" {{isset($result) && $result->status=="1"?'selected':''}}>Active </option>
                            <option value="0" {{isset($result) && $result->status=="0"?'selected':''}}>De-Active</option>
                        </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">Role<span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select mb-2  mr-sm-2 " required>
                              <option>Select Role</option>
                                @foreach($role as $key => $value)
                               <option value="{{$key}}"{{isset($result) && $result->roles ?'selected':''}}>{{ucfirst($value)}}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger validationclass" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
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
