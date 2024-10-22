<!DOCTYPE html>
<html>
<head>
    <title>Blog Post Details</title>
</head>
<body>
    <h1>{{ $blogpost->title }}</h1>
    <p>{{ $blogpost->content }}</p>
    <p>Author: {{ $blogpost->author }}</p>
    @if($blogpost->image)
    <img src="{{ asset('images/' . $blogpost->image) }}" alt="{{ $blogpost->title }}">
    @endif
    <a href="{{ route('admin.blogposts.index') }}">Back to List</a>
</body>
</html>
