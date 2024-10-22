@extends('layouts.adminCommon')
@section('content')
<div class="container"></br>
<div class="row">
   <div class="col-md-12 text-right">
    <a href="{{route('pages.index')}}" class="btn btn-primary btn-sm">Back</a></br></br>
</div>
</div>
    <div class="row">
        <div class="card"></br>
            <div class="col-md-10">
                <form action="{{route('pages.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$data->id ?? ''}}" />
                    <label>Title</label>
                    <input type="text" name="title" value="{{$data->title ?? ''}}" class="form-control" /></br>
                    @error('title')
                    <div class="validationclass text-danger">{{$message}}</div>
                    @enderror
                    <label>Slug</label>
                    <input type="text" name="slug" value="{{$data->slug ?? ''}}" class="form-control" /></br>
                    @error('slug')
                    <div class="validationclass text-danger">{{$message}}</div>
                    @enderror
                    <label>Banner Section</label>
                        <textarea name="banner_section" class="form-control">{{$data->banner_section ?? ''}}</textarea></br>
                    <label>Content Section</label>
                        <textarea name="content" class="form-control">{{$data->content ?? ''}}</textarea></br>
                    
                    <button class="btn btn-primary btn-sm" value="submit">Submit</button></br></br>
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