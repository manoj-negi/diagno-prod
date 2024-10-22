
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('subscription.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$data->id ?? ''}}">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Title<span class="text-danger">*<span></label>
                                <input type="text" name="title" class="form-control" value="{{old('title', $data->title ?? '')}}" placeholder="Title" required />
                            </div>
                            @error('title')
                            <div class="validationclass text-danger">{{$message}}</div>
                            @enderror
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Price<span class="text-danger">*<span></label>
                                <input type="text" name="price" class="form-control" value="{{old('price', $data->price ?? '')}}" placeholder="Price" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" required/>
                            </div>
                            @error('price')
                            <div class="validationclass text-danger">{{$message}}</div>
                            @enderror
                             <div class="mb-3">
                                <label class="form-label" min="1" for="basic-default-fullname">Days<span class="text-danger">*<span></label>
                                <input type="text" name="days" class="form-control" min="0" value="{{old('days', $data->days ?? '')}}" placeholder="days" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" required />
                            </div>
                            @error('days')
                            <div class="validationclass text-danger">{{$message}}</div>
                            @enderror
                            {{-- <div class="mb-3">
                                <textarea name="content" class="form-control">{{old('content', $data->content ?? '')}}</textarea>
                            </div>
                            @error('content')
                                <div class="validationclass text-danger">{{$message}}</div>
                                @enderror --}}
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Plan<span class="text-danger">*<span></label>
                                <select name="plan[]"  class="multiple form-control mb-2  mr-sm-2 select2 form-select"
                                multiple="multiple" id="">
                                    @foreach($plan as $key => $plans)
                                    <option value="{{$key}}"{{(in_array($key, old('plan', [])) || isset($data) && $data->plans->contains($key)) ? 'selected' :''}}>{{$plans}}</option>
                                    @endforeach
                                </select>
                             </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{isset($data) && $data->status=="1"?'selected':''}}>Active
                                    </option>
                                    <option value="0" {{isset($data) && $data->status=="0"?'selected':''}}>De-Active
                                    </option>
                                </select>
                            </div>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
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

