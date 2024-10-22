@extends('layouts.adminCommon')

@section('content')
<div class="container"></br>
<div class="row">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="col-md-12 text-right">
        <a href="{{ route('offer.index') }}" class="btn btn-primary btn-sm">Back</a></br></br>
    </div>
</div>
    <div class="row">
        <div class="card"></br>
            <div class="col-md-12">
            <form method="POST" action="{{ route('offer.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ old('id', $data->offer_id ?? '') }}" />

        <div class="row">
            <div class="col-6">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $data->title ?? '') }}" class="form-control" />
                @error('title')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $data->description ?? '') }}</textarea>
                @error('description')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', isset($data->start_date) ? \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') : '') }}" class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                @error('start_date')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', isset($data->end_date) ? \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') : '') }}" class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                @error('end_date')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

           

            <div class="col-6 mt-2">
                <label>Discount Percentage</label>
                <input type="number" step="0.01" name="discount_percentage" value="{{ old('discount_percentage', $data->discount_percentage ?? '') }}" class="form-control" />
                @error('discount_percentage')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Maximum Discount</label>
                <input type="number" step="0.01" name="maximum_discount" value="{{ old('maximum_discount', $data->maximum_discount ?? '') }}" class="form-control" />
                @error('maximum_discount')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Minimum Purchase Amount</label>
                <input type="number" step="0.01" name="minimum_purchase_amount" value="{{ old('minimum_purchase_amount', $data->minimum_purchase_amount ?? '') }}" class="form-control" />
                @error('minimum_purchase_amount')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Offer Code</label>
                <input type="text" name="offer_code" value="{{ old('offer_code', $data->offer_code ?? '') }}" class="form-control" />
                @error('offer_code')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Offer Type</label>
                <select name="offer_type" class="form-control">
                    <option value="">Select</option>
                    <option value="percentage" {{ old('offer_type', $data->offer_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed_amount" {{ old('offer_type', $data->offer_type ?? '') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                </select>
                @error('offer_type')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Applicable To</label>
                <select name="applicable_to" class="form-control">
                    <option value="">Select</option>
                    <option value="all_users" {{ old('applicable_to', $data->applicable_to ?? '') == 'all_users' ? 'selected' : '' }}>All Users</option>
                    <option value="new_users" {{ old('applicable_to', $data->applicable_to ?? '') == 'new_users' ? 'selected' : '' }}>New Users</option>
                    <option value="existing_users" {{ old('applicable_to', $data->applicable_to ?? '') == 'existing_users' ? 'selected' : '' }}>Existing Users</option>
                </select>
                @error('applicable_to')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-6 mt-2">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ old('status', $data->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $data->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ old('status', $data->status ?? '') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-2">
                <label>Terms and Conditions</label>
                <textarea name="terms_and_conditions" class="form-control">{{ old('terms_and_conditions', $data->terms_and_conditions ?? '') }}</textarea>
                @error('terms_and_conditions')
                <div class="validationclass text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">Submit</button>
        
    </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.24.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('description');
    CKEDITOR.config.allowedContent = true;
</script>

@endsection
