@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-12 pt-4">
            <div class="card">
                <div class="card-body">
                <form action="{{route('mail.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$result->id ?? ''}}">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Title:<span class="text-danger">*</span></label>
                             <input type="text" value="{{ old('title', isset($result) && isset($result->title) ? $result->title : '') }}" name="title" class="form-control" required>
                               @error('title')
                                <span class="validationclass text-danger">{{$message}}</span>
                               @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                             <label class="form-label">Subject<span class="text-danger">*</span></label>
                               <input type="text" value="{{ old('mail_subject', isset($result) && isset($result->mail_subject) ? $result->mail_subject : '') }}" name="mail_subject" class="form-control" required>
                               @error('subject')
                              <div class="validationclass text-danger">{{$message}}</div>
                             @enderror
                        </div>
                    </div>
                   
                    <div class="row mt-4">
                        <div class="col-md-12">
                              <label class="form-label">Message Category<span class="text-danger">*</span></label>
                    <select class="form-control" name="mail_key">
                        <option value="">--Select Category--</option>
                        <option value="contact-us" {{isset($result) && $result->mail_key=="contact-us"?"selected":''}}>Contact Us</option>
                        <option value="password-reset" {{isset($result) && $result->mail_key=="password-reset"?"selected":''}}>Password Reset</option>
                        <option value="signup" {{isset($result) && $result->mail_key=="signup"?"selected":''}}>SignUp</option>
                        <option value="doctor-signup" {{isset($result) && $result->mail_key=="doctor-signup"?"selected":''}}>Doctor SignUp</option>
                        <option value="patient-signup" {{isset($result) && $result->mail_key=="patient-signup"?"selected":''}}>Patient SignUp</option>
                        <option value="document-upload" {{isset($result) && $result->mail_key=="document-upload"?"selected":''}}>Doctor Document Upload</option>
                        <option value="contact-us-user" {{isset($result) && $result->mail_key=="contact-us-user"?"selected":''}}>Contact User</option>
                        <option value="document-accepted" {{isset($result) && $result->mail_key=="document-accepted"?"selected":''}}>Doctor Document Accepted</option>
                        <option value="document-rejected" {{isset($result) && $result->mail_key=="document-rejected"?"selected":''}}>Doctor Document Rejected</option>
                        <option value="login" {{isset($result) && $result->mail_key=="login"?"selected":''}}>Login</option>
                        <option value="thank-you" {{isset($result) && $result->mail_key=="thank-you"?"selected":''}}>Thank you</option>
                        <option value="question-for-patient" {{isset($result) && $result->mail_key=="question-for-patient"?"selected":''}}>Question For Patient</option>
                    </select></br>
                    @error('mail_key')
                    <div class="validationclass text-danger">{{$message}}</div>
                    @enderror
                        </div>
                    </div>
                   
                  <div class="row">
                        <div class="col-md-12">
                             <label class="form-label">Message Content<span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" required>{{ old('content', isset($result) && isset($result->content) ? $result->content : '') }}</textarea>
                    @error('content')
                    <span class="validationclass text-danger">{{$message}}</span>
                    @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                             <label class="form-label"> Status </label>
                            <select name="status" class="form-control">
                                <option value="1" {{isset($result) && $result->status=="1"?'selected':''}}>Active
                                <option value="0" {{isset($result) && $result->status=="0"?'selected':''}}>De-Active
                                </option>
                                </option>
                    </select>
                        </div>
                    </div>
                   <div class="row mt-4">
                        <div class="col-md-12">
                             <a class="btn btn-warning btn-sm" href="{{route('mail.index')}}">Back</a>
                    <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </div>

                  
                   
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
        CKEDITOR.replace( 'content' );
        CKEDITOR.config.allowedContent = true;
</script>
@endsection