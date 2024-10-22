@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <h1>Add New Pincode</h1>

    {{-- Error message display --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Pincode form --}}
    <form action="{{ route('pincodes.store') }}" method="POST">
        @csrf

        {{-- Pincode input --}}
        <div class="form-group">
            <label for="pincode">Pincode:</label>
            <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required>
        </div>

        {{-- Submit button --}}
        <button type="submit" class="btn btn-success mt-3">Add Pincode</button>
    </form>
</div>
@endsection
