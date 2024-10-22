@extends('layouts.adminCommon')
@section('content')
<div class="container"></br>
<div class="row">
        <div class="col-md-12 text-right">
    <a class="btn btn-primary btn-sm" href="{{route('permission.index')}}">Back</a></br></br>
</div>
</div>
    <div class="row">
        <div class="card"></br>
            <div class="col-md-10">

                <form action="{{route('permission.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$edit->id ?? ''}}" />
                    <label>Permission:</label>
                    <input type="text" name="permission" class="form-control" value="{{$edit->permission ?? ''}}"/></br>
                    @error('role')
                    <div class="alert text-danger">{{$message}}</div>
                    @enderror
                    <button type="submit" value="submit" class="btn btn-primary btn-sm mb-2">Submit</button>
                </div>
                </form>
                   
@endsection