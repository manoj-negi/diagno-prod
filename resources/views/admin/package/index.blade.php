@extends('layouts.adminCommon')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            @can('dashboard_support_list')
                <a class="btn btn-primary btn-sm my-3" href="{{ route('package.create') }}">Create</a>
            @endcan
            @if(Auth::user()->roles->contains(4))
                <a class="btn btn-primary btn-sm my-3" href="{{ route('package.create') }}">Create</a>
            @endif
            @if ($message = Session::get('msg'))
                <div class="alert alert-danger alert-dismissible text-left mt-2 alertmsg mb-3">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
        
        <div class="col-lg-12">
            <!-- Placeholder for success message -->
            <div class="alert alert-success d-none" id="success-message">
                Package  updated successfully.
            </div>

            <div class="demo-inline-spacing">
                <div class="card">
                    <div class="px-0 mt-0">
                        <div class="tab-pane fade active show" id="document">
                            <div class="table-responsive text-nowrap">
                                <form action="" method="GET">
                                    <!-- Add pagination and search options here -->
                                </form>
                                <form action="{{ url('package-amount-update') }}" method="POST">
                                    @csrf
                                  
                                    <table class="table table-bordered m-2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                @if(Auth::user()->roles->contains(4))
                                                    <th>Available</th>
                                                @endif
                                                <th>Package Name</th>
                                                <th>Profiles Count</th>
                                                @if(Auth::user()->roles->contains(4))
                                                    <th>Main Package Name</th>
                                                @endif
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data as $key => $result)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    @if(Auth::user()->roles->contains(4))
                                                        <td>
                                                            <div class="form-check form-check-inline mt-3">
                                                                <input class="form-check-input checkbox" data-id="{{ $result->id }}" type="checkbox" value="1" {{ $result->is_selected == '1' ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>{{ $result->package_name ?? 'Package not selected' }}</td>
                                                    <td>Total profile: {{ $result->profiles->count() ?? '0' }}</td>
                                                    @if(Auth::user()->roles->contains(4))
                                                        <td>{{ $result->parentPackage->package_name ?? '--' }}</td>
                                                    @endif
                                                    <td>{{ number_format($result->amount, 2) }}</td>
                                                    <td>
                                                        <a class="btn btn-primary btn-sm" href="{{ route('package.show', $result->id) }}">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                        @if(Auth::user()->roles->contains(1) || ($result->lab_id == Auth::user()->id))
                                                            <a class="btn btn-primary btn-sm" href="{{ route('package.edit', $result->id) }}">
                                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{ url('package-destroy', $result->id) }}" onclick="return confirm('Are you sure you want to delete this item?');">
                                                                <button class="btn btn-danger btn-sm" type="button">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No Data Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{ $data->links('pagination::bootstrap-4') }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
   $(document).ready(function() {
      $(document).on('change', '.checkbox', function() {
         var packageId = $(this).attr('data-id');
         var isSelected = $(this).is(':checked') ? 1 : 0;

         $.ajax({
            url: '{{ route("package.updateSelection") }}',  // Make sure this route is defined in your routes/web.php
            type: 'POST',
            data: {
               _token: '{{ csrf_token() }}',
               id: packageId,
               is_selected: isSelected
            },
            success: function(response) {
               if (response.success) {
                  // Show the success message and then hide it after 3 seconds
                  $('#success-message').removeClass('d-none').fadeIn();
                  setTimeout(function() {
                     $('#success-message').fadeOut();
                  }, 3000);
               } else {
                  alert('Failed to update package selection');
               }
            },
            error: function(xhr) {
               console.log(xhr.responseText);
               alert('An error occurred while updating package selection');
            }
         });
      });
   });
</script>
@endsection
