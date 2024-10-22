@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5">
            @if (session('msg'))
                <div class="validationclass text-danger pt-2">{{ session('msg') }}</div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('package.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $data->id ?? '' }}">

                        <div class="row">
                            <!-- Package Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-fullname">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" name="package_name" class="form-control" value="{{ old('package_name', $data->package_name ?? '') }}" placeholder="Package Name" required />
                                    @error('package_name')
                                        <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
<!-- Main Package Selection -->
@if(Auth::user()->roles->contains(4))
<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label" for="basic-default-fullname">Select Main Package <span class="text-danger">*</span></label>
        <select name="parent_id" id="parent_id" class="form-control" required>
            <option value="">Select Main Package</option>
            @foreach($packageName as $value)
                <option value="{{ $value->id }}" {{ old('parent_id', $data->parent_id ?? '') == $value->id ? 'selected' : '' }}>
                    {{ $value->package_name }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="validationclass text-danger pt-2">{{ $message }}</div>
        @enderror
    </div>
</div>
@endif


                          <!-- Lab Selection (For Admin Role) -->
                          @if(Auth::user()->roles->contains(1))
                                <!-- <div class="col-md-6">
                                    <label for="lab_id" class="form-label">Select Lab<span class="text-danger">*</span></label>
                                    <select name="lab_id" class="form-control labId" id="lab_id">
                                        <option value="">Select Lab</option>
                                        @foreach($labs as $lab)
                                            <option value="{{ $lab->id }}" {{ old('lab_id', $data->lab_id ?? '') == $lab->id ? 'selected' : '' }}>
                                                {{ $lab->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-md-6">
    <label for="lab_id" class="form-label">Select Lab<span class="text-danger">*</span></label>
    <select name="lab_id" class="form-control labId" id="lab_id">
        <option value="">Select Lab</option>
        @foreach($labs as $lab)
            <option value="{{ $lab->id }}"
                {{ old('lab_id', $data->lab_id ?? '') == $lab->id || (empty(old('lab_id')) && empty($data->lab_id) && $lab->name == 'Diagnomitra') ? 'selected' : '' }}>
                {{ $lab->name }}
            </option>
        @endforeach
    </select>
</div>

                            @else
                                @if(Auth::user()->roles->contains(4))
                                    <input type="hidden" name="lab_id" value="{{ Auth::user()->id }}"/>
                                @else
                                    <input type="text" name="lab_id" value="{{ old('lab_id', $data->lab_id ?? '') }}"/>
                                @endif
                            @endif


                            <!-- Amount -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-fullname">Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" class="form-control" value="{{ old('amount', $data->amount ?? '') }}" placeholder="Amount" required />
                                    @error('amount')
                                        <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Package Profile Selection -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Package Profile </label>

                                @if(isset($selectedProfile))

                                <select name="profile_id[]" id="profileData" class="select2Profile form-control profile_id" multiple="multiple">
                                    @foreach($selectedProfile as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, old('profile_id', $selectedProfile->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                            {{ $item->profile_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('profile_id')
                                    <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                @enderror

                                @else
    <!-- Optionally, you can handle the case where $selectedProfile is not defined or is not iterable -->
    
    <select name="profile_id[]" id="profileData" class="select2Profile form-control profile_id" multiple="multiple">
                                   
                                </select>

@endif

                            </div>

                            <!-- TAT (Turnaround Time) Field -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="TAT">TAT (Turnaround Time)<span class="text-danger">*</span></label>
                                    <input type="text" id="TAT" name="TAT" class="form-control" value="{{ old('TAT', $data->TAT ?? '') }}" placeholder="TAT"/>
                                </div>
                                @error('TAT')
                                    <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- No. of Parameters Field -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="no_of_parameters">No. of Parameters<span class="text-danger">*</span></label>
                                    <input type="number" id="no_of_parameters" name="no_of_parameters" class="form-control" value="{{ old('no_of_parameters', $data->no_of_parameters ?? '') }}" placeholder="No. of Parameters"/>
                                </div>
                                @error('no_of_parameters')
                                    <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload for Admin -->
                            @if(Auth::user()->roles->contains(1))                                <div class="form-group col-6 mb-3 {{ $errors->has('image') ? 'has-error' : '' }}">
                                    <label for="image" class="form-label">Image<span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" />
                                    @error('image')
                                        <span class="validationclass text-danger" style="position: absolute;top: 90px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <!-- Image Preview -->
                            <div class="col-md-6">
                                @if(isset($data) && !empty($data->image))
                                    <img class="img-fluid rounded my-4" src="{{ url('uploads/package', $data->image ?? '') }}" height="80px" width="160px" alt="Package Image">
                                @endif
                            </div>

                            <!-- Lifestyle Package Checkbox for Admin -->
                            @if(Auth::user()->roles->contains(0))                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="lb mt-3">
                                            <label class="form-label">Lifestyle Package<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="in">
                                            <input class="form-check-input ms-2 mt-0" type="checkbox" name="is_lifestyle" {{ old('is_lifestyle', $data->is_lifestyle ?? false) ? 'checked' : '' }} value="1">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Frequently Booking Package Checkbox for Admin -->
                            <div class="col-md-12">
    <div class="d-flex align-items-center">
        <!-- Frequently Booking Package -->
        <div class="d-flex align-items-center">
            <div class="lb mt-3">
                <label class="form-label">Frequently Booking Package<span class="text-danger">*</span></label>
            </div>
            <div class="in">
                <input class="form-check-input ms-2 mt-0" type="checkbox" name="is_frequently_booking" {{ old('is_frequently_booking', $data->is_frequently_booking ?? false) ? 'checked' : '' }} value="1">
            </div>
        </div>

        <!-- Lifestyle Booking Package -->
        <div class="d-flex align-items-center mx-5">
            <div class="lb mt-3">
                <label class="form-label">Lifestyle Booking Package<span class="text-danger">*</span></label>
            </div>
            <div class="in">
                <input class="form-check-input ms-2 mt-0" type="checkbox" name="is_lifestyle" {{ old('is_lifestyle', $data->is_lifestyle ?? false) ? 'checked' : '' }} value="1">
            </div>
        </div>
    </div>
</div>


                        <!-- Form Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('package.index') }}" class="btn btn-warning btn-sm">Back</a>
                                <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    function formatItem(item) {
        if (item.loading) return item.text;
        return "<div class='select2-result-item'>" + (item.profile_name || item.text) + "</div>";
    }

    function formatItemSelection(repo) {
        return repo.profile_name || repo.text;
    }

    $('.select2Profile').select2({
        ajax: {
            url: "{{ url('/get-package-profile') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    lab_id: "{{ Auth::user()->hasRole('admin') ? '' : Auth::user()->id }}"
                };
            },
            processResults: function(data) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        placeholder: 'Select package profiles',
        minimumInputLength: 1,
        escapeMarkup: function(markup) { return markup; },

        templateResult: formatItem,
        templateSelection: formatItemSelection
    });
});
</script>
@endsection
