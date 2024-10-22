
@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5 ">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    <form action="{{route('medicines.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$medicine->id ?? ''}}">
                        <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label" for="name">{{__('Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old('name', $medicine->name ?? '')}}" placeholder="Name" required/>
                              </div>
                                @error('name')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                            </div> 
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label" for="name">{{__('Spenish Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="spenish_name" id="spenish_name" class="form-control" value="{{old('spenish_name', $medicine->spenish_name ?? '')}}" placeholder="Spenish Name" />
                              </div>
                                @error('spenish_name')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                            </div> 
                        </div>
                           <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">{{__('Description')}} </label>
                                    <textarea type="text" name="description" placeholder="Description"   class="form-control" >{{old('description', $medicine->description ?? '')}}</textarea>
                                </div>
                                @error('description')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">{{__('Spenish Description')}}</label>
                                    <textarea type="text" name="spenish_description" placeholder="Description"   class="form-control" >{{old('spenish_description', $medicine->spenish_description ?? '')}}</textarea>
                                </div>
                                @error('spenish_description')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                            </div>
                           </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">{{__('Dosage')}}<span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" placeholder="Dosage" name="dosage" required>{{ old('dosage', $medicine->dosage ??'')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">{{__('Spenish Dosage')}}<span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" placeholder="Dosage" name="spenish_dosage" >{{ old('spenish_dosage', $medicine->spenish_dosage ??'')}}</textarea>
                                    </div>
                                </div>
                            </div>
                              
                             <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">{{__('Side Effects ')}}<span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" placeholder="Side Effects" name="side_effects" required>{{old('side_effects', $medicine->side_effects ?? '')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">{{__('Side Effects ')}}<span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" placeholder="Side Effects" name="spenish_side_effects" >{{old('spenish_side_effects', $medicine->spenish_side_effects ?? '')}}</textarea>
                                    </div>
                                </div>
                             </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">{{__('Precautions')}} <span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" placeholder="Precautions" name="precautions" required>{{old('precautions', $medicine->precautions ??'')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="basic-default-company">{{__('Precautions')}} <span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" placeholder="Precautions" name="spenish_precautions" required>{{old('spenish_precautions', $medicine->spenish_precautions ??'')}}</textarea>
                                    </div>
                                </div>
                            </div>
                              
                                
                            <div class="mb-3">
                                <label class="form-label">{{__('Status')}} </label>
                                <select name="status" class="form-control form-select">
                                    <option value="1" {{isset($medicine) && $medicine->status=="1"?'selected':''}}>Active
                                    </option>
                                    <option value="0" {{isset($medicine) && $medicine->status=="0"?'selected':''}}>De-Active
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm mb-2">Back</a>
                                <button type="submit" value="submit" class="btn btn-primary btn-sm mb-2 ">Submit</button>
                            </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
