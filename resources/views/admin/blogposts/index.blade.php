@extends('layouts.adminCommon')

@section('content')


<div class="container">


    <div class="row pb-2">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm my-3" href="{{ route('admin.blogposts.create') }}">Create</a>
            @if ($message = Session::get('msg'))
            <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <div class="paging-section justify-content-end">
                                <form class="index-form">
                                    <div class="search_bar d-flex">
                                        <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="filter">
                                            <option selected="true" disabled="disabled">Select Status</option>
                                            <option class="text-dark" value="1" {{ request()->get('filter') == '1' ? 'selected' : '' }}>Active</option>
                                            <option class="text-dark" value="0" {{ request()->get('filter') == '0' ? 'selected' : '' }}>De-Active</option>
                                        </select>
                                        <input type="text" id="search" name="search" placeholder="Search" class="form-control" value="{{ request()->get('search') ?? '' }}" placehoder="Search" />
                                        <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        <a class="form-control src-btn" href="{{ route('admin.blogposts.index') }}"><i class="fa fa-rotate-left"></i></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <table class="table table-responsive tabel-hover">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>CONTENT</th>
                               
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($blogPosts as $key => $post)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                @if($post->image)
        <img src="{{ env('AWS_URL') . '/' . $post->image }}" alt="Post Image" style="max-width: 80px; max-height: 80px;">
    @else
        No Image
    @endif
                                </td>
                                <!-- <td>
                                    @if($post->image)
                                    <img src="{{ asset('uploads/blogposts/' . $post->image) }}" alt="Post Image" style="max-width: 80px; max-height: 80px;">
                                    @else
                                    No Image
                                    @endif
                                </td> -->
                                <td> @if(strlen($post->title)>=20)

{{substr($post->title, 0,  20).'...' ??''}}
@else

{{$post->title??''}}

@endif</td>
                                <!-- <td>{{ $post->content }}</td> -->
                                <td> @if(strlen($post->content)>=30)

                        {{substr($post->content, 0,  60).'...' ??''}}
                        @else

                        {{$post->content??''}}

                        @endif</td>
                              
                        <td>
    <input type="checkbox" class="toggle-status" data-id="{{ $post->id }}" data-toggle="toggle" data-on="Active" data-off="Inactive"  {{ $post->is_published ? 'checked' : '' }}>
</td>

                                <td>
                                    <a href="{{ route('admin.blogposts.edit', $post->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <form action="{{ route('admin.blogposts.destroy', $post->id) }}" method="post" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger show_confirm btn-sm" onclick="return confirm('Are you sure you want to delete this post?')"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="pagination">
                    {{ $blogPosts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("document").ready(function () {
        setTimeout(function () {
            $("div.alert").remove();
        }, 3000); // 3 secs
    });
</script>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
   
        $('.toggle-status').change(function() {
            var postId = $(this).data('id');
            var status = $(this).prop('checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("admin.blogposts.toggleStatus") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: postId,
                    is_published: status
                },
                success: function(response) {
                    if (response.success) {
                        // alert('Status updated successfully');
                    } else {
                        alert('Failed to update status: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log('Error - ' + errorMessage);
                    console.log(xhr.responseText);
                    alert('Error updating status: ' + errorMessage);
                }
            });
        });
   
</script>


@endsection
