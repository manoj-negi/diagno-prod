
@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 pt-5">
            @if (session('msg'))
                <div class="validationclass text-danger pt-2">{{ session('msg') }}</div>
            @endif
            <div class="card">
                <div class="card-body">
                    <!-- Update form action based on the existence of $id -->
                    <form action="{{ isset($id) ? route('profile.update', $id) : route('profile.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if(isset($id))
                            @method('PUT')
                        @endif
                        
                        <input type="hidden" name="id" value="{{$data->id ?? '' }}">
                        <input type="hidden" name="url" value="{{ request()->input('url') }}">

                        <div class="row">
                            <!-- Profile Input/Dropdown -->
                            @if(Auth::user()->roles->contains(1)) <!-- Admin Role -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="profile_name">Profile Name</label>
                                    <input type="text" name="profile_name" class="form-control" id="profile_name" value="{{ old('profile_name', $data->profile_name ?? '') }}" placeholder="Enter new profile name"/>
                                </div>
                            </div>
                            @else
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="diagnomitra_profile">Select Diagnomitra Profile</label>
                                    <select name="diagnomitra_profile" class="form-control" id="diagnomitra_profile">
                                        <option value="">Select Profile</option>
                                        @foreach($diagnomitraProfiles as $profile)
                                            <option value="{{ $profile->id }}" {{ old('diagnomitra_profile', $data->diagnomitra_profile ?? '') == $profile->id ? 'selected' : '' }}>
                                                {{ $profile->profile_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <!-- Amount Field -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="number" id="amount" name="amount" class="form-control"
                                           value="{{ old('amount', $data->amount ?? '') }}" placeholder="Amount"/>
                                </div>
                                @error('amount')
                                    <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lab Selection (For Admin Role) -->
                            @if(Auth::user()->roles->contains(1))
                                <div class="col-md-6">
                                    <label for="lab_id" class="form-label">Select Lab<span class="text-danger">*</span></label>
                                    <select name="lab_id" class="form-control labId" id="lab_id">
                                        <option value="">Select Lab</option>
                                        @foreach($labs as $lab)
                                            <option value="{{ $lab->id }}" {{ old('lab_id', $data->lab_id ?? '') == $lab->id ? 'selected' : '' }}>
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

                            <!-- Test Selection -->
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label" for="testsData">{{ __('Test Name') }} <span class="text-danger">*</span></label>
                                <select name="test_id[]" class="form-control select2test" id="testsData" multiple>
                                    @foreach($selectedTests as $testId => $testName)
                                        <option value="{{ $testId }}" selected>{{ $testName }}</option>
                                    @endforeach
                                </select>
                                @error('test_id')
                                    <div class="validationclass text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea id="ckeditor" name="description" class="form-control">{{ old('description', $data->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="validationclass text-danger pt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Image Upload (For Admin Role) -->
                            @if(Auth::user()->roles->contains(1))
                                <div class="form-group col-md-6 mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control"/>
                                    @error('image')
                                        <span class="validationclass text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    @if(isset($data->image))
                                        <img class="img-fluid rounded my-4" src="{{ url('uploads/profile', $data->image) }}" height="80" width="160" alt="Profile Image">
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Form Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('profile.index') }}" class="btn btn-warning btn-sm">Back</a>
                                <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles and Scripts -->
<style>
   .modal-dialog {
      background-color: white !important;
      border-radius: 8px !important;
   }
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
    function formatItem(item) {
        if (item.loading) return item.text;
        return "<div class='select2-result-item'>" + item.test_name + "</div>";
    }

    function formatItemSelection(repo) {
        return repo.test_name || repo.text;
    }

    // Initialize Select2 with all tests
    $('.select2test').select2({
        ajax: {
            url: "{{ url('/get-test') }}", // Endpoint to fetch all tests
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 10) < data.total_count 
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for a test...',
        minimumInputLength: 1,
        escapeMarkup: function(markup) { return markup; },
        templateResult: formatItem,
        templateSelection: formatItemSelection
    });

    // Fetch and populate tests based on profile selection
    $('#diagnomitra_profile').change(function() {
        var profileId = $(this).val();
        $.ajax({
            url: "{{ url('/get-tests-by-profile') }}", // Endpoint to fetch tests by profile
            type: 'GET',
            data: { profile_id: profileId },
            success: function(response) {
                var select = $('#testsData').select2();
                var selectedIds = response.map(function(item) { return item.id; });

                // Fetch all tests and select the ones from the profile
                $.ajax({
                    url: "{{ url('/get-test') }}", // Fetch all tests again to keep the full list
                    type: 'GET',
                    dataType: 'json',
                    success: function(allTests) {
                        select.empty();
                        $.each(allTests.items, function(index, item) {
                            var isSelected = selectedIds.includes(item.id);
                            var newOption = new Option(item.test_name, item.id, isSelected, isSelected);
                            select.append(newOption);
                        });
                        select.val(selectedIds).trigger('change.select2');
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred while fetching all tests:", error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("An error occurred while fetching tests by profile:", error);
            }
        });
    });
});

</script>
@endsection
