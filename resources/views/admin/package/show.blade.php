@extends('layouts.adminCommon')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <!-- Package Details -->
        <div class="card-body">
            <div class="row">
                <div class="mb-3 col-md-12">
                    <h5 class="mb-4">Package Details</h5>

                    <table class="table">
                        <tbody>
                            <tr>
                                <th width="80">Package Name</th>
                                <td>{{ $data->package_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th width="80">Amount</th>
                                <td>{{ $data->amount ?? '' }}</td>
                            </tr>
                            <tr>
                                <th width="80">Banner</th>
                                <td>
                                    @if(isset($data->image) && !empty($data->image))
                                    <img class="img-fluid rounded my-4"
                                        src="{{ url('uploads/package', $data->image) }}" height="80px" width="160px"
                                        alt="Package Image">
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Profile Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->profiles ?? [] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->profile_name ?? '' }}</td>
                            <td>₹{{ number_format($item->amount ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="d-flex pt-3">
                    <a href="{{ route('package.index') }}" class="btn btn-warning btn-sm mb-3">Back</a>&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#example').DataTable(); // Initialize DataTable
    });
</script>
@endsection
