@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <h1>Pincodes List</h1>
    <div class="d-flex justify-content-between">
        <a href="{{ route('pincodes.create') }}" class="btn btn-primary">Add New Pincode</a>

        {{-- Import CSV Form with right alignment --}}
        <form action="{{ route('pincodes.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <input type="file" name="csv_file" id="csv_file" class="form-control" style="display: none;" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-success mx-2" id="chooseFileButton">Import CSV</button>
                    <button type="submit" class="btn btn-primary" style="display:none;" id="submitButton">Submit</button>
                </div>
            </div>
        </form>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    @if (session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pincode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    @foreach ($pincodes as $pincode)
        <tr>
            <td>{{ $loop->iteration }}</td> <!-- Serial number -->
            <td>{{ $pincode->pincode }}</td>
            <td>
                <a href="{{ route('pincodes.edit', $pincode->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('pincodes.destroy', $pincode->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

    </table>
</div>

{{-- Add JavaScript to toggle the file input and submit button --}}
<script>
    document.getElementById('chooseFileButton').addEventListener('click', function () {
        document.getElementById('csv_file').click(); // Open file input when clicking the button
    });

    document.getElementById('csv_file').addEventListener('change', function () {
        document.getElementById('submitButton').style.display = 'inline-block'; // Show submit button when file is selected
    });
</script>
@endsection
