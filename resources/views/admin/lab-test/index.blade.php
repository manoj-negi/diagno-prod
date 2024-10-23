@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <div class="row pb-2">
        <div class="col-md-12 text-right">
            <a class="btn btn-success btn-sm my-3" data-bs-toggle="modal" data-bs-target="#importTests" style="color: white;">Import Tests</a>
            <a class="btn btn-primary btn-sm my-3" href="{{ url('lab-test/create') }}">Create</a>
            @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
        <div class="cssloader d-none">
            <div class="sh1"></div>
            <div class="sh2"></div>
            <h4 class="lt">Importing data.... Do not close</h4>
        </div>
        <div class="modal fade" id="importTests" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="importForm" action="{{ url('import-csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="nameBasic" class="form-label">Select Lab</label>
                                    <select name="lab" class="form-control mr-1" style="color:#697a8d!important;">
                                        <!-- <option value="1">{{ env('APP_NAME', 'DiagnoMitra') }}</option> -->
                                        @foreach($labs ?? [] as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="nameBasic" class="form-label">Select CSV File</label>
                                    <input type="file" name="file" class="form-control" accept=".csv">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="col-md-12">
                    <form action="">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="paging-section justify-content-start">
                                    <select style="width:75px;" class="form-select form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
                                        <option value="10" {{ app('request')->input('pagination') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="30" {{ app('request')->input('pagination') == '30' ? 'selected' : '' }}>30</option>
                                        <option value="50" {{ app('request')->input('pagination') == '50' ? 'selected' : '' }}>50</option>
                                        <option value="70" {{ app('request')->input('pagination') == '70' ? 'selected' : '' }}>70</option>
                                        <option value="100" {{ app('request')->input('pagination') == '100' ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-11">
                                <div class="paging-section justify-content-end row">
                                    <div class="col-md-2 mt-3"></div>
                                    <div class="col-md-2 mt-3">
                                        @if(Auth::user()->roles->contains(1))
                                        <select name="lab" id="labDropdown" class="form-control mr-1" style="color:#697a8d!important;" onchange="fetchLabTests(this.value)">
                                            <option value="">Select Lab</option>
                                            @foreach($labs ?? [] as $item)
                                            <option value="{{ $item->id }}" {{ app('request')->input('lab') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>
                                    <div class="col-md-3 mt-3 d-flex">
                                        <input type="text" id="search" name="search" placeholder="Search" class="form-control" value="{{ app('request')->input('search') }}">
                                        <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        <a class="form-control src-btn" href="{{ url('lab-test') }}"><i class="fa fa-rotate-left"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered" id="labTestsTable">
                        <thead>
                            <tr>
                                <th style="width: 10px!important;">S.No</th>
                                <th>Test Name</th>
                                @if(Auth::user()->roles->contains(1)) <th>Lab</th> @endif
                                <th>Test Price</th>
                                <th style="width: 160px!important;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be injected here by JavaScript -->
                            @foreach($data as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->test_name ?? '' }}</td>
                                @if(Auth::user()->roles->contains(1)) 
                                <td>{{ $value->lab_name ?? '-' }}</td>
                                @endif
                                <td>{{ number_format($value->amount ?? 0, 2) }}</td>
                                <td>
                                @if(Auth::user()->roles->contains(1) || Auth::user()->id == $value->lab_id)
                                <a href="{{ route('lab-test.edit', $value->id) }}" class="btn btn-primary btn-sm mr-1">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('lab-test.destroy', $value->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-md-12">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function fetchLabTests(labId, search = '') {
    $.ajax({
        url: `/get-lab-test/${labId}`,  // Ensure this URL matches your route
        type: 'GET',
        data: { search: search },  // Pass search query as a parameter
        success: function(response) {
            let testsHtml = '';
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(test, index) {
                    testsHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${test.test_name ?? ''}</td>
                            <td>${test.lab_name ?? ''}</td>  <!-- Lab name from response -->
                            <td>${parseFloat(test.amount ?? 0).toFixed(2)}</td>
                            <td>
                                <a href="/lab-test/edit/${test.id}" class="btn btn-primary btn-sm mr-1"><i class="fa fa-edit"></i></a>
                                <form method="POST" action="/lab-test/destroy/${test.id}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    `;
                });
            } else {
                testsHtml = '<tr><td colspan="5" class="text-center">No test data found.</td></tr>';
            }

            $('#labTestsTable tbody').html(testsHtml);
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr);
        }
    });
}
</script>





@endsection
