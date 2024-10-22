@extends('layouts.adminCommon')
@section('content')
<div class="container"></br>
<div class="row">
        <div class="col-md-12 text-right">
    <a href="{{route('medicaltest.index')}}" class="btn btn-primary btn-sm">Back</a></br></br>
    </div>
</div>
    <div class="row">
        <div class="card"></br>
            <div class="col-md-12">
                <form action="{{route('medicaltest.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$dataview->id ?? ''}}" />
                    <div class="row">
                    <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" value="{{$dataview->name ?? ''}}" class="form-control" /></br>
                    @error('name')
                    <div class="validationclass text-danger">{{$message}}</div>
                    @enderror
                    </div>
                    <div class="col-md-6">
                    <label>Description</label>
                    <input type="text" name="description"
                        class="form-control" value="{{$dataview->description ?? ''}}"/></br>
                        @error('description')
                        <div class="validationclass text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Image</label>
                            <input type="file" name="image" value="{{$dataview->image ?? ''}}"
                                class="form-control " /></br>
                        </div>
                        <div class="col-md-2">
                            @if(isset($dataview) && !empty($dataview->image))
                            <img src="{{url('Images'.'/'.$dataview->image)}}" width="50px" height="50px"/></br>
                            @endif
                        </div>
                        <div class="col-md-6">
                        <label class="">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{isset($dataview) && $dataview->status=="1"?"selected" : ''}}>Active
                            </option>
                            <option value="0" {{isset($dataview) && $dataview->status=="0"?"selected" : ''}}>In Active
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <label>Doctor category</label>
                        <select name="category[]"class="multiple form-control mb-2  mr-sm-2 select2" multiple="multiple" id="">
                            @foreach($doctor as $key => $dat)
                            <option value="{{$key}}" {{ (in_array($key, old('category', [])) || isset($dataview) && $dataview->tests->contains($key)) ? 'selected' :''}}>{{$dat}}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-6">
                        <label>Diseases</label>
                        <select name="diseases[]" class="multiple form-control mb-2  mr-sm-2 select2" multiple="multiple" id="">
                            @foreach($datas as $key => $dat)
                            <option value="{{$key}}" {{(in_array($key, old('diseases', [])) || isset($dataview) && $dataview->test->contains($key)) ? 'selected' :''}}>{{$dat}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                        <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-sm mt-3"
                                    value="submit">Submit</button></br></br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
   
    $(document).ready(function() {  
       $('.multiple').select2({
          
           placeholder: "Select",
                   allowClear: true
       });
   });
     </script>
     </body>
   

@endsection