@extends('layouts.adminCommon')
@section('content')

<div class="container mt-3">

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title">
            <!-- {{ $title }} -->
        </h4>
    </div>

    <div class="card-body">
        <form action="{{ route("mails-template.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{isset($MailTemplate)?$MailTemplate->id:''}}" name="id">
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-4 {{ $errors->has('from_name') ? 'has-error' : '' }}">
                        <label class="form-label" for="from_name">{{ __('From Name') }}*</label>
                        <input type="text" id="from_name" name="from_name" placeholder="{{ __('From Name') }}" class="form-control" value="{{ old('from_name', isset($MailTemplate) ? $MailTemplate->from_name : '') }}" required>
                        @if($errors->has('from_name'))
                            <p class="help-block">
                                {{ $errors->first('from_name') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4 {{ $errors->has('subject') ? 'has-error' : '' }}">
                        <label class="form-label" for="subject">{{ __('Subject') }}*</label>
                        <input type="text" id="subject" name="subject" placeholder="{{ __('Subject') }}" class="form-control" value="{{ old('subject', isset($MailTemplate) ? $MailTemplate->subject : '') }}" required>
                        @if($errors->has('subject'))
                            <p class="help-block">
                                {{ $errors->first('subject') }}
                            </p>
                        @endif
                    </div>
                </div>

                 <!-- <div class="col-6">
                    <div class="form-group mb-4 {{ $errors->has('from_email') ? 'has-error' : '' }}">
                        <label class="form-label" for="from_email">{{ __('From Email') }}*</label>
                        <input type="text" id="from_email" name="from_email" placeholder="{{ __('From Email') }}" class="form-control" value="{{ old('from_email', isset($MailTemplate) ? $MailTemplate->from_email : '') }}" required>
                        @if($errors->has('from_email'))
                            <p class="help-block">
                                {{ $errors->first('from_email') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4 {{ $errors->has('replay_from_email') ? 'has-error' : '' }}">
                        <label class="form-label" for="replay_from_email">{{ __('Replay From Email') }}*</label>
                        <input type="text" id="replay_from_email" name="replay_from_email" placeholder="{{ __('Replay From Email') }}" class="form-control" value="{{ old('replay_from_email', isset($MailTemplate) ? $MailTemplate->replay_from_email : '') }}" required>
                        @if($errors->has('replay_from_email'))
                            <p class="help-block">
                                {{ $errors->first('replay_from_email') }}
                            </p>
                        @endif
                    </div>
                </div>  -->

                <div class="col-6">
                    <div class="form-group mb-4 {{ $errors->has('category') ? 'has-error' : '' }}">
                        <label class="form-label" for="category">{{ __('Category') }}</label>
                        <select class="form-control" id="category" name="category" >
                            <option value="">Mail Category</option>
                            @foreach($categories as $key => $category)
                            <option value="{{$key}}" {{isset($MailTemplate) && $MailTemplate->category==$key?'selected':''}}>{{$category}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('category'))
                            <p class="help-block">
                                {{ $errors->first('category') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group mb-4 {{ $errors->has('message') ? 'has-error' : '' }}">
                <label class="form-label" for="description">{{ __('Message') }}</label>
                <textarea id="message" name="message" class="form-control editor">{{ old('message', isset($MailTemplate) ? $MailTemplate->message : '') }}</textarea>
                @if($errors->has('message'))
                    <p class="help-block">
                        {{ $errors->first('message') }}
                    </p>
                @endif
            </div>

            <div>
                <input class="btn btn-primary" type="submit" value="{{ trans('save') }}">
            </div>
        </form>
    </div>
</div>

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
   ClassicEditor
       .create(document.querySelector('#message'))
       .catch(error => {
           console.error(error);
       });
</script>

<!-- <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
        ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .then( editor => {
                console.log( editor );
             } )
          .catch( error => {
          console.error( error );
        } );
</script> -->

@endsection
