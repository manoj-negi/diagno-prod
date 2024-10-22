@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content mt-3">      
            <div class="col-md-12">
                 <div class="card">
                    <div class="card-body">
                <form action="{{route('role.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$edit->id ?? ''}}" />
                    <label class="form-label">Role:</label>
                    <input type="text" name="role" class="form-control" value="{{$edit->role ?? ''}}"/>
                    @error('role')
                    <div class="validationclass text-danger">{{$message}}</div>
                    @enderror
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Permission</label>
                            <select name="permission[]"  class="multiple  mb-2  mr-sm-2 select2 form-select"
                            multiple="multiple" id="">
                                @foreach($permission as $key => $result)
                                <option value="{{$key}}"{{(in_array($key, old('permission', [])) || isset($edit) && $edit->permissions->contains($key)) ? 'selected' :''}}>{{$result}}</option>
                                @endforeach
                            </select>                       
                        </div>
                    </div>
                    <div class="mb-3 pt-3">
                    <a href="{{ route('role.index')}}" class="btn btn-warning btn-sm">Back</a>
                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>                 
@endsection