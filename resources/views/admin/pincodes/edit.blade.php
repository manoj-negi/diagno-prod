@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <h1>Edit Pincode</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pincodes.update', $pincode->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="pincode">Pincode:</label>
            <input type="text" name="pincode" class="form-control" value="{{ $pincode->pincode }}">
        </div>

        <button type="submit" class="btn btn-success mt-3">Update Pincode</button>
    </form>
</div>
@endsection
