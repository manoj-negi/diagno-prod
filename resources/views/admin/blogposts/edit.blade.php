<!DOCTYPE html>
<html>
<head>
    <title>Edit Blog Post</title>
</head>
<body>
    <h1>Edit Blog Post</h1>
    <form action="{{ route('admin.blogposts.update', $blogpost->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ $blogpost->title }}" required>
        
        <label for="content">Content</label>
        <textarea name="content" id="content" required>{{ $blogpost->content }}</textarea>
        
        <label for="author">Author</label>
        <input type="text" name="author" id="author" value="{{ $blogpost->author }}" required>
        
        <label for="image">Image</label>
        <input type="file" name="image" id="image">
        
        <button type="submit">Update</button>
    </form>
</body>
</html>
