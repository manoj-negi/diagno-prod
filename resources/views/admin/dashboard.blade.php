@extends('layouts.adminDashboard') 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="row">
      <div class="col-lg-12 mb-4 order-0">
         <div class="card">
            <div class="d-flex align-items-end row">
               <div class="col">
                  <h3 class="card-title text-primary" style=" margin: 20px; text-transform: capitalize;">Welcome Back @if(Auth::user()->name  == 'Admin') 
                  DiagnoMitra
                  @else
                  {{ Auth::user()->name }}
                  @endif
               </h3>
                  <h5 class="card-title" style=" margin: 20px;"style="color:#566a7f!important"></h5>
                  <div class="card-body">
                  </div>
               </div>
               <div class="col-sm-4 text-center text-sm-left">
                  <div class="card-body pb-0 px-0 px-md-4">
                     <img class ="imgdata" src="{{url('Images/170713289965c0c7e3982d8imgpsh_fullsize_anim-removebg-preview-(1).png')}}"
                        class="img-fluid"
                        alt="View Badge User"
                        data-app-dark-img="{{url('illustrations/man-with-laptop-dark.png')}}"
                        data-app-light-img="{{url('illustrations/man-with-laptop-light.png')}}">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Total Revenue -->
   <div class="row">
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsTotal ?? 0}}</h3>
                     <small>Total Booking</small>
                  </div>
                  <span class="badge bg-label-primary rounded-circle p-2">
                  <i class="bx bx-calendar bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsPending ?? 0}}</h3>
                     <small>Pending Booking</small>
                  </div>
                  <span class="badge bg-label-success rounded-circle p-2">
                  <i class="bx bx-plus-medical bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsComplete ?? 0}}</h3>
                     <small>Completed Booking</small>
                  </div>
                  <span class="badge bg-label-danger rounded-circle p-2">
                  <i class=" bx bx-calendar bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsCollected ?? 0}}</h3>
                     <small>Collected Booking</small>
                  </div>
                  <span class="badge bg-label-danger rounded-circle p-2">
                     <i class="bx bxs-calendar-alt bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsArrived ?? 0}}</h3>
                     <small>Arrived Booking</small>
                  </div>
                  <span class="badge bg-label-danger rounded-circle p-2">
                  <i class="bx bxs-calendar-edit bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsReportUploaded ?? 0}}</h3>
                     <small>Report Uploaded Booking</small>
                  </div>
                  <span class="badge bg-label-danger rounded-circle p-2">
                  <i class="bx bxs-calendar-week bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 col-xl-4 col-12 mt-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div class="content-left">
                     <h3 class="mb-0">{{$AppointmentsInprogress ?? 0}}</h3>
                     <small>Inprogress Booking</small>
                  </div>
                  <span class="badge bg-label-danger rounded-circle p-2">
                  <i class="bx bx-calendar-edit bx-sm"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4 mt-3">
         <div class="card">
            <div class="row row-bordered g-0">
               <div class="col-md-12">
                  <h5 class="card-header m-0 me-2 pb-3"> Booking</h5>
                  <ul class="nav nav-tabs" id="myTab" role="tablist" style="justify-content: space-evenly;">
                     <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Today Booking</button>
                     </li>
                     <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Old Booking</button>
                     </li>
                     <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">UpComing Booking</button>
                     </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                     <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <table class="table table-responsive">
                           <tr>
                              <th>Lab</th>
                              <th>patient Name</th>
                              <th>time</th>
                              <th>date</th>
                             
                           </tr>
                           @forelse($todayAppointments as $key => $todayAppointment)
                           <tr>
                           <td>
    @if(isset($todayAppointment->hospital))
        <a href="{{ url('lab/'.$todayAppointment->hospital->id.'/edit') }}">
            {{ $todayAppointment->hospital->name }}
        </a>
    @else
        ---
    @endif
</td>

                              <td><a href="{{url('patient/'.$todayAppointment->patient->id)}}">{{ isset($todayAppointment->patient) ? $todayAppointment->patient->name : '---' }}</a></td>
                              <td>{{ ucfirst($todayAppointment->time) ?? '---' }}</td>
                              <td>{{ ucfirst($todayAppointment->date) ?? '---' }}</td>
                           </tr>
                           @empty
                           <tr>
                              <td colspan="5"class="text-center">No Booking available for today</td>
                           </tr>
                           @endforelse
                        </table>
                     </div>
                     <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <table class="table table-responsive">
        <tr>
            <th>Lab</th>
            <th>Patient Name</th>
            <th>Time</th>
            <th>Date</th>
        </tr>
        @forelse($oldAppointments as $key => $oldAppointment)
        <tr>
            {{-- Lab name --}}
            <td>
    @if(isset($todayAppointment->hospital))
        <a href="{{ url('lab/'.$todayAppointment->hospital->id.'/edit') }}">
            {{ $todayAppointment->hospital->name }}
        </a>
    @else
        ---
    @endif
</td>


            {{-- Patient name --}}
            <td>
                <a href="{{ url('patient/' . $oldAppointment->patient->id) }}">
                    {{ isset($oldAppointment->patient) ? $oldAppointment->patient->name : '---' }}
                </a>
            </td>

            {{-- Time and Date --}}
            <td>{{ ucfirst($oldAppointment->time) ?? '---' }}</td>
            <td>{{ ucfirst($oldAppointment->date) ?? '---' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No Booking available for old</td>
        </tr>
        @endforelse
    </table>
</div>

                     </div>
                     <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <table class="table table-responsive">
                           <tr>
                              <th>Lab</th>
                              <th>patient Name</th>
                              <th>time</th>
                              <th>date</th>
                           
                           </tr>
                           @forelse($upComingAppointments as $key=> $upComingAppointment)
                           <tr>
                           <td>
    @if(isset($todayAppointment->hospital))
        <a href="{{ url('lab/'.$todayAppointment->hospital->id.'/edit') }}">
            {{ $todayAppointment->hospital->name }}
        </a>
    @else
        ---
    @endif
</td>

                              <td><a href="{{url('patient/'.$upComingAppointment->patient->id)}}">{{ isset($upComingAppointment->patient) ? $upComingAppointment->patient->name : '---' }}</a></td>
                              <td>{{ ucfirst($upComingAppointment->time) ?? '---' }}</td>
                              <td>{{ ucfirst($upComingAppointment->date) ?? '---' }}</td>
                             
                           </tr>
                           @empty
                           <tr>
                              <td colspan="5" class="text-center">No  Booking available for UpComing </td>
                           </tr>
                           @endforelse
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="text-center">
         <div class="dropdown">
         </div>
      </div>
   </div>
   <div class="row mb-3">
      <div class="col-md-6 mt-4">
         <div class="card h-100">
            <table class="table table-responsive">
               <h3 style=" margin: 20px;">Recent Review<a style="float:right;" class="btn btn-primary btn-sm" href="{{route('ratingreviews.index')}}">View</a></h3>
               <tr>
                  <th>Lab Name</th>
                  <th>patient Name</th>
                  <th>Ratings</th>
                  <th>Review</th>
               </tr>
           
            </table>
         </div>
      </div>
      @can('dashboard_support_list')
      <div class="col-md-6 mt-4">
         <div class="card h-100">
            <table class="table table-responsive">
               <h3 style=" margin: 20px;">Support<a style="float:right;" class="btn btn-primary btn-sm" href="{{route('supports.index')}}">View</a></h3>
               <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
               </tr>
               @forelse($supports as $support) 
               <tr>
                  <td>{{$support->name}}</td>
                  <td>{{$support->email}}</td>
                  <td>{{$support->phone}} </td>
               </tr>
               @empty
               <tr>
                  <td colspan="5" class="text-center"> Support is not found </td>
               </tr>
               @endforelse
            </table>
         </div>
      </div>
      @endcan
      @can('dashboard_labs_test_list')
      <div class="col-md-6 mt-4">
         <div class="card h-100">
            <table class="table table-responsive">
               <h3 style=" margin: 20px;">Lab Tests<a style="float:right;" class="btn btn-primary btn-sm" href="{{route('lab-test.index')}}">View</a></h3>
               <tr>
                  <th>Test Name</th>
                  <th>Amount</th>
                  <th>Admin Status</th>
               </tr>
             
            </table>
         </div>
      </div>
      @endcan
   </div>
   <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
      <div class="d-flex">
         <div class="me-2">
         </div>
      </div>
   </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--/ Total Revenue -->
</div>
</div>
@endsection