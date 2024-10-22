@extends('layouts.adminCommon')
@section('content')



<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card mb-4">
      <!-- Account -->
      <div class="card-body">
         <div class="row">
            <div class="mb-3 col-md-4">
               <h5 class="mb-4">Profile Details</h5>
               <div class="d-flex justify-content-center">
                  @if(!empty($data->profile_image))
                  <img class="img-fluid  my-4" src="{{ url('uploads/patient/'.$data->profile_image) }}" height="400px"
                     width="125px"
                     style="box-shadow: 0px 0px 9px -2px #000000;width: 150px;border-radius: 100%;height: 150px;">
                  @else
                  <img class="img-fluid  my-4" src="{{url('uploads/profile-imges/user.png')}}" height="400px"
                     width="125px"
                     style="box-shadow: 0px 0px 9px -2px #000000;width: 150px;border-radius: 100%;height: 150px;">
                  @endif
               </div>
               <table class="table">
                  <tbody>
                     <tr>
                        <th width="80">Name</th>
                        <td> {{$data->name ?? ''}}</td>
                     </tr>

                     <tr>
                        <th width="80">Email</th>
                        <td><a href=""> {{$data->email ??''}}</a></td>
                     </tr>
                     <tr>
                        <th width="80">Status</th>
                        <td>
                           <span class="badge bg-label-success">{{$data->status=="0"?'In Active':'Active' ?? ''}}</span>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <div class="d-flex pt-3">
                  <a href="{{route('patient.index')}}" class="btn btn-warning btn-sm mb-3">Back</a>&nbsp;
                  <!-- <button  type="button" class="btn btn-info btn-sm mb-3"  data-bs-toggle="modal" data-bs-target="#presetquestion">Preset Question Answers</button>&nbsp; -->
                  <a href="{{route('patient.edit',$data->id)}}" class="btn btn-primary btn-sm mb-3">Edit</a>&nbsp;
               </div>
            </div>
            <div class="mb-3 col-md-8">
               <h5 class="mb-4">Other Details</h5>
               <div class="table-responsive text-nowrap">
                  <table class="table">
                     <tbody>
                        <tr>
                           <th>Phone</th>
                           <td>{{$data->number ?? ''}}</td>
                        </tr>
                        <tr>
                           <th width="80">Address: </th>
                           <td>{{$data->address ?? ''}}</td>
                        </tr>
                        <tr>
                           <th width="80">Dob: </th>
                           <td>{{$data->dob ?? ''}}</td>
                        </tr>


                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="card mb-4">
      <!-- Account -->
      <div class="card-body">
         <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">

               <div class="card-body">
                  <h4>Booking</h4>
                  <table class="table table">
                     <tr>
                        <th style="width: 10px!important;">S.No</th>
                        <th>Patient Name</th>
                        <th>Test Name</th>
                        <th>Lab Name</th>
                        <th>Report File</th>


                     </tr>
                     @if(count($appointments)>0)
                     @php
                     isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
                     isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

                     $i = (($page-1)*$paginate)+1;
                     @endphp
                     @endif
                     @forelse($appointments as $appointment)

                     <tr>
                        <td>{{$i++ ?? ''}}</td>
                        <td>{{isset($appointment->patient) ? $appointment->patient->name : ''}}</td>
                        <td>{{isset($appointment->test) ? $appointment->test->test_name : ''}}</td>
                        <td>{{isset($appointment->hospital) ? $appointment->hospital->name : ''}}</td>
                        <td> @if(!empty($appointment->report_image))

                           <div class="download_button mt-2">
                              <a href="{{ url('uploads/appointment/', $appointment->report_image ?? '') }}" download>
                                 <button type="button" class="btn btn-primary btn-sm">Download</button>
                              </a>
                           </div>
                           @else
                           <p>
                              <button type="button" class="btn btn-xs btn-primary upload-button"
                                 data-bs-target="#exampleModalupload" user-id="{{$data->id}}">Upload</button>
                           </p>
                           @endif
                        </td>

                     </tr>

                     @empty
                     <tr>
                        <td colspan="5" class="text-center">No Appoiment Bill available</td>
                     </tr>
                     @endforelse
                  </table>
                  <div id="pagination">{{{ $appointments->links() }}}</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="card mb-4">
      <div class="card-body">
         <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">

               <div class="card-body">
                  <h4>Patient Family</h4>
                  <table class="table table">
                     <thead>
                        <tr>
                           <th style="width: 10px!important;">S.No</th>
                           <th>Name</th>
                           <th>Relation</th>
                           <th>Email</th>
                           <th>Phone</th>
                           <th>Age</th>
                           <th>Gender</th>
                           <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse($PatientFamily as $family)
                        <tr>
                           <td>{{$loop->index + 1}}</td>

                           <td>{{$family->name}}</td>
                           <td>{{$family->patient_type}}</td>
                           <td>{{$family->email}}</td>
                           <td>{{$family->phone}}</td>
                           <td>{{$family->age}}</td>
                           <td>{{$family->gender}}</td>

                           <td>{{$family->status}}</td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="10" class="text-center">No Appointment Bill available</td>
                        </tr>
                        @endforelse
                     </tbody>

                  </table>
                  <div class="d-flex justify-content-end mt-4">
                     {{ $patientFamilies->links() }}
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="card mb-4">
      <div class="card-body">
         <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">

               <div class="card-body">
                  <h4>Patient Prescriptions</h4>
                  <table class="table table">
                     <thead>
                        <tr>
                           <th style="width: 10px!important;">S.No</th>
                           <th>Prescriptions Title</th>
                           <th>Uploaded Date</th>
                           <th>Prescriptions File</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse($prescription as $value)
                        <tr>
                           <td>{{$loop->index + 1}}</td>
                           <td>{{!empty($value->prescription_title) ? $value->prescription_title : 'No title Available'}}</td>
                           <td>{{date('d F Y h:i A',strtotime($value->created_at))}}</td>
                           <td>
                              @if(!empty($value->prescription_file))
                              <div class="download_button mt-2">
                                 <a href="{{ url('uploads/pre/', $value->prescription_file ?? '') }}" download>
                                    <button type="button" class="btn btn-primary btn-sm">Download</button>
                                 </a>
                              </div>
                           @else
                           No File Available
                           @endif
                           </td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="10" class="text-center">No Prescriptions Uploaded Yet</td>
                        </tr>
                        @endforelse
                     </tbody>

                  </table>
                  <div class="d-flex justify-content-end mt-4">
                     {{ $patientFamilies->links() }}
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
@endsection