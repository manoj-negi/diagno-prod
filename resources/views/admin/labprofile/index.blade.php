@extends('layouts.adminCommon')

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12 text-right">
         <?php $url = Request::segment(1); ?>
         <a class="btn btn-primary btn-sm my-3" href='{{ url("profile/create?url=$url") }}'>Create</a>
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
                Profile  updated successfully.
            </div>
      <div class="col-lg-12">
         <div class="demo-inline-spacing">
            <div class="card">
               <div class="px-0 mt-0">
                  <div class="tab-pane fade active show" id="document">
                     <div class="table-responsive text-nowrap">
                        <form action="">
                           <div class="row">
                              <div class="col-md-1">
                                 <div class="paging-section justify-content-start">
                                    <select style="width:75px;" class="form-select" name="pagination" id="itemsPerPage" onchange="this.form.submit()">
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
                                    <div class="col-md-2 mt-3">
                                    </div>
                                    <div class="col-md-2 mt-3">
                                       @if(Auth::user()->roles->contains(1) && Request::segment(1) != 'diagno-profiles')
                                       <select name="lab" id="labDropdown" class="form-control mr-1" style="color:#697a8d!important;" onchange="fetchLabProfiles(this.value)">
                                          <option value="">Select Lab</option>
                                          @foreach($labs ?? [] as $item)
                                          <option value="{{$item->id ?? ''}}" {{ app('request')->input('lab') == $item->id ? 'selected' : '' }}>{{$item->name ?? ''}}</option>
                                          @endforeach
                                       </select>
                                       @endif
                                    </div>
                                    <div class="col-md-3 mt-3 d-flex">
                                       <input type="text" id="search" name="search" placeholder="Search" class="form-control" value="{{ app('request')->input('search') }}">
                                       <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                       <a class="form-control src-btn" href="{{ url('profile') }}"><i class="fa fa-rotate-left"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                        <form>
                      
                           <table class="table table-bordered m-2">
                              <thead>
                                 <tr>
                                    <th style="width: 10px!important;">ID</th>
                                    @if(Auth::user()->roles->contains(4))
                                    <th>Available</th>
                                    @endif
                                    <th>Profile Name</th>
                                    @if(Auth::user()->roles->contains(4))
                                    <!-- <th>Main Profile Name</th> -->
                                    @endif
                                    <th>Total Tests</th>
                                    @if(Auth::user()->roles->contains(1))  
                                    <th>Lab Name</th> 
                                    @endif
                                    <th>Amount</th>
                                    <th style="width: 160px!important;">Action</th>
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
                                    <td>{{ $result->profile_name ?? 'Profile not selected' }}</td>
                                    <!-- Rest of the columns -->
                                    @if(Auth::user()->roles->contains(4))
                                    <!-- <td>
                                       @php
                                          $subPackages = \App\Models\LabProfile::where('id', $result->parent_id)->first();
                                       @endphp
                                       {{ $subPackages->profile_name ?? '--' }}
                                    </td> -->
                                    @endif
                                    <td>{{ $result->labsTestsProfiles->count() }}</td> 
                                    @if(Auth::user()->roles->contains(1))
                                    <td>
                                       {{ isset($result->lab_name) ? $result->lab_name : 'Diagnomitra' }}
                                    </td>
                                    @endif
                                    <td>
                                       {{ number_format($result->amount, 2) }}
                                    </td>
                                    <td>
                                       <a class="btn btn-primary btn-sm" href="{{ route('profile.show', $result->id) }}">
                                          <i class="fa fa-eye" aria-hidden="true"></i>
                                       </a>
                                       @if(Auth::user()->roles->contains(1) || Auth::user()->id == $result->lab_id)
                                       <a class="btn btn-primary btn-sm" href='{{ route('profile.edit', $result->id)."?url=$url" }}'>
                                          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                       </a>
                                       <a onclick="return confirm('Are you sure you want to delete this item?');" href="{{ url('profile-destroy', $result->id) }}" class="btn btn-danger btn-sm">
                                          <i class="fa fa-trash" aria-hidden="true" style="color:#fff"></i>
                                       </a>
                                       @endif
                                    </td>
                                 </tr>
                                 @empty
                                 <tr>
                                    <td colspan="8" class="text-center">No Data Found</td>
                                 </tr>
                                 @endforelse
                              </tbody>
                           </table>
                           {{ $data->links('pagination::bootstrap-4') }}

                           <div id="pagination">{{ $data->links() }}</div>
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
      // Listen for checkbox changes
      $(document).on('change', '.checkbox', function() {
         var profileId = $(this).attr('data-id');
         var isSelected = $(this).is(':checked') ? 1 : 0;

         // Make an AJAX request to update the profile selection
         $.ajax({
            url: '/update-profile-selection', // Route to handle selection updates
            type: 'POST',
            data: {
               _token: '{{ csrf_token() }}', // Include CSRF token
               profile_id: profileId,
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

      // Auto-remove alerts after 3 seconds
      setTimeout(function() {
         $("div.alert").remove();
      }, 3000);
   });

   function fetchLabProfiles(labId) {
      if (labId) {
         $.ajax({
            url: '/get-lab-profiles/' + labId,
            type: 'GET',
            success: function(data) {
               updateProfileTable(data.profiles);
            },
            error: function(xhr) {
               console.error("Error fetching profiles:", xhr);
            }
         });
      } else {
         updateProfileTable([]);
      }
   }

   function updateProfileTable(profiles) {
      var tableBody = $('tbody');
      tableBody.empty();

      if (profiles.length > 0) {
         profiles.forEach(function(profile, index) {
            var profileName = profile.profile_name || '--';
            var totalTests = profile.total_tests || 0;
            var labName = profile.lab_name || '--';
            var amount = profile.amount !== undefined ? profile.amount.toFixed(2) : '--';

            var row = `
               <tr>
                  <td>${index + 1}</td>
                  <td>${profileName}</td>
                  <td>${totalTests}</td>
                  <td>${labName}</td>
                  <td>${amount}</td>
                  <td>
                     <a class="btn btn-primary btn-sm" href="/profile/${profile.id}">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                     </a>
                     <a class="btn btn-primary btn-sm" href="/profile/${profile.id}/edit">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                     </a>
                     <a onclick="return confirm('Are you sure you want to delete this item?');" href="/profile/${profile.id}/destroy" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash" aria-hidden="true" style="color:#fff"></i>
                     </a>
                  </td>
               </tr>
            `;

            tableBody.append(row);
         });
      } else {
         tableBody.append(`
            <tr>
               <td colspan="6" class="text-center">No Data Found</td>
            </tr>
         `);
      }
   }
</script>
@endsection
