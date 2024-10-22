@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.blogposts.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $blogPost->id ?? '' }}" />
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $blogPost->title ?? '') }}" required />
                                @error('title')
                                    <span class="validationclass text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Content<span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control" rows="3" required>{{ old('content', $blogPost->content ?? '') }}</textarea>
                                @error('content')
                                    <span class="validationclass text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author<span class="text-danger">*</span></label>
                                <input type="text" name="author" class="form-control" value="{{ old('author', $blogPost->author ?? '') }}" required />
                                @error('author')
                                    <span class="validationclass text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">is_published</label>
                                <select name="is_published" class="form-control">
                                    <option value="1" {{ old('is_published', isset($blogPost) && $blogPost->is_published == 1 ? 'selected' : '') }}>Active</option>
                                    <option value="0" {{ old('is_published', isset($blogPost) && $blogPost->is_published == 0 ? 'selected' : '') }}>Inactive</option>
                                </select>
                                @error('is_published')
                                    <span class="validationclass text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
    <label for="image" class="form-label">Image</label>
    <input type="file" name="image" class="form-control" />
    @if(isset($blogPost) && !empty($blogPost->image))
        <img src="{{ Storage::url($blogPost->image) }}" class="img-fluid mt-2" alt="Blog Post Image">
    @endif
    @error('image')
        <span class="validationclass text-danger">{{ $message }}</span>
    @enderror
</div>
<div class="col-md-6">
                                <label class="form-label">Slug<span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $blogPost->slug ?? '') }}" required />
                                @error('slug')
                                    <span class="validationclass text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-warning">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
