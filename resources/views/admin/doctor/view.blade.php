@extends('layouts.adminCommon')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <!-- Doctor Prifile -->

      <div class="col-xl-6 col-lg-6 col-md-6 ">
        <div class="card mb-4">
          <div class="card-body">
            <div class="user-avatar-section">
              <div class=" d-flex align-items-center flex-column">
                <img class="img-fluid rounded my-4" src="{{url('uploads/profile-imges',$doctor['profile_image'] ?? '')}}" height="200" width="210" alt="User avatar">
              </div>
            </div>
            <div class="d-flex justify-content-around flex-wrap my-2 py-1">
              <div class="d-flex align-items-start me-4 mt-3 gap-3">
                <span class="badge bg-label-primary p-2 rounded"></span>
                <div>
                </div>
              </div>
              <div class="d-flex align-items-start mt-3 gap-3">

                <div>
                </div>
              </div>
            </div>
            <h5 class="pb-2 border-bottom mb-4">Doctor Details</h5>
            <div class="info-container">
              <ul class="list-unstyled">
                <li class="mb-3">
                  <span class="fw-bold me-2">name:</span>
                  <span>{{$doctor->name ?? ''}}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Email:</span>
                  <span>{{$doctor->email ??''}}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Status:</span>
                  <span class="badge bg-label-success">{{$doctor->status=="0"?'In Active':'Active' ?? ''}}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Contact:</span>
                  <span>{{$doctor->number ?? ''}}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Speciality:</span>
                  <span>
                    @foreach ($doctor->speciality as $sk => $item)
                      {{($sk>0)?',':""}} {{$item->name}}
                    @endforeach
                  </span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Education:</span>
                  <span>
                    @foreach ($doctor->educations as  $sk =>  $item)
                       {{($sk>0)?',':""}} {{$item->name}}
                    @endforeach
                  </span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">BIO:</span>
                  <span>{{$doctor->doctor_bio?? ''}}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Hospital:</span>
                  <span>
                    @foreach ($doctor->hospitals as  $sk => $item)
                      {{($sk>0)?',':""}}{{$item->name}}
                    @endforeach
                  </span>
                </li>

                <li class="mb-3">
                  <span class="fw-bold me-2">Subscription Name:</span>
                  <span>
                    {{$startSubscription->subscriptions->title ??''}}
                  </span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Subscription Start Date:</span>
                  <span>{{$startSubscription['start_date'] ??''}}</span>
                </li>
                <li class="mb-3">
                  <span class="fw-bold me-2">Subscription End Date:</span>
                  <span>{{$startSubscription['end_date'] ??''}}</span>
                </li>
              </ul>
              <div class="d-flex justify-content-center pt-0">
              <a href="{{route('doctor.index')}}" class="btn btn-warning btn-sm mb-3">Back</a>&nbsp;
              <a href="{{route('doctor.edit',$doctor->id)}}" class="btn btn-primary btn-sm mb-3">Edit</a>&nbsp;
              </div>
            </div>
          </div>
        </div>
     <!-- /User Card -->
        <!-- Plan Card -->

        <!-- /Plan Card -->
      </div>

      @php
          $doctorConsult =!empty($doctor->types_of_consultation)?json_decode($doctor->types_of_consultation,true):[];
      @endphp

      <div class="col-6">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Type of Consultations</h4>

                        @if (count($types_of_consultation)>0)
                        <div class="row mt-5">
                            <form action="{{route('doctor.update',$doctor->id)}}" method="post">
                                 @csrf
                                 @method('PUT')
                            @foreach ($types_of_consultation as $key => $value)
                            <div class="mb-3" style="display: flex;
                            justify-content: space-between;">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="checkbox" value="{{$value->id}}" name="type_of_consultation[]" id="flexCheckDefault{{$key}}" onchange="price{{$value->id}}.disabled = !this.checked" @if(isset($doctorConsult[$value->id]))
                                   checked
                                    @endif >
                                    <label class="form-check-label fs-5" for="flexCheckDefault{{$key}}">
                                        {{$value->consultation_name}}
                                    </label>
                                  </div>
                                  <div class="d-inline-block">
                                    <input class="form-control" type="text" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" placeholder="Price" id="price{{$value->id}}" name="price[{{$value->id}}]" aria-label="Price example" value="{{$doctorConsult[$value->id]??''}}"@if(!isset($doctorConsult[$value->id]))
                                    disabled
                                     @endif required >
                                  </div>
                                </div>
                            @endforeach
                            <span class="text-danger">
                                @error('type_of_consultation')
                                 {{$message}}
                                @enderror
                            </span>
                            <span class="text-danger">
                                @error('price.*')
                                {{$message}}
                               @enderror
                            </span>
                            <div>

                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-12 text-center">
                               <p>No Data Found</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                 <h4>Doctor Questions</h4>

            <div class="row justify-content-end">
                <div class="col-6">
                    <form  class="view-form" action="{{route('doctor.show',$doctor->id)}}">
                        @csrf
                    <select class="form-select text-muted mr-1" id="consult_type" name="consult_type" onchange="document.querySelector('.view-form').submit()">
                        <option class="text-dark" value="">All Questions</option>
                 @foreach ($doctor_consultation as $item => $consult)
                 <option class="text-dark" value="{{$consult->id}}"{{request()->get('consult_type')==$consult->id?'selected':''}}>{{$consult->consultation_name}}</option>
                 @endforeach
                    </select >
                </form>
                </div>

                <table class="table table-responsive mt-4">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 10% !important">#</th>
                        <th scope="col">Questions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if (count($doctor_question) > 0)
                          @foreach ($doctor_question as $item=>$value)
                          <tr>
                            <th scope="row">{{$item+1}}</th>
                            <td>{{$value->question}}</td>
                          </tr>
                          @endforeach
                      @else
                      @php
                      $questionnodata=1;
                      @endphp
                      @endif

                    </tbody>
                  </table>
                  @if (isset($questionnodata))
                    <div class="row">
                    <div class="col-12 text-center"><p>No Data Found</p></div>
                    </div>
                  @endif
            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

      <div class="col-xl-12 col-lg-12 col-md-12 ">
        <!-- User Card -->
        <div class="card mb-4">
          <div class="card-body -mr-9-bottom-20">
                  <div class="row" style="justify-content: space-between">
                    <div class="col-4"> <h4>Doctor Availability </h4></div>
                    <div class="col-4">
                        <form  class="view-form-aval" action="{{route('doctor.show',$doctor->id)}}">
                        @csrf
                    <select class="form-select text-muted mr-1" id="doctor_availability" name="doctor_availability" onchange="document.querySelector('.view-form-aval').submit()">
                        <option class="text-dark" value="">All Hospitals</option>
                 @foreach ($doctor->hospitals as $item => $hospital)
                 <option class="text-dark" value="{{$hospital->id}}"{{request()->get('doctor_availability')==$hospital->id?'selected':''}}>{{$hospital->name}}</option>
                 @endforeach
                    </select >
                </form></div>
                  </div>
                        @php
                          $weekdays=['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        @endphp
                         <div class="row pt-3 mt-3">
                            <div class="col-12">
                                <table class="table  table-borderless">
                                    <thead>
                                      <tr>
                                          <th></th>
                                        <th scope="col">Week</th>
                                        <th scope="col">Open Time</th>
                                        <th scope="col">Close Time</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($doctor_avail)>0)
                                        @foreach ($doctor_avail as $key=>$value)
                                        <tr>
                                            @if($key==0||$key%7==0)
                                             <tr>
                                            <td><h5>{{$value->hospitals->name??'-'}}</h5></td></tr>
                                            @endif
                                            <td></td>
                                           <td> <input class="form-check-input" type="checkbox" value="" id="week" @if ($value->status=='1')
                                              checked
                                           @endif  disabled>
                                               <label class="form-check-label fs-5 pl-4" for="week">
                                                  {{$weekdays[$value->week_day-1]??'-'}}
                                               </label></td>
                                           <td> <input type="time" class="form-control" id="time_open" value="{{$value->start_time??'-'}}"  disabled></td>
                                           <td> <input type="time" class="form-control" id="time_close" value="{{$value->end_time??'-'}}" disabled></td>
                                         </tr>
                                        @endforeach

                                      @else
                                      @php
                                       $nodataaval=1;
                                      @endphp
                                        @endif
                                    </tbody>
                                  </table>

                                  @if (isset($nodataaval))
                                  <div class="row">
                                  <div class="col-12 text-center"><p>No Data Found</p></div>
                                  </div>
                                @endif
                            </div>
                         </div>
    </div>
        </div>
      </div>
        <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                   <table class="table table-responsive tabel-hover">
                    <h4>Review</h4>
                    <tr>
                    <th style="width: 5% !important;">S.No</th>
                     <th>Patient</th>
                     <th>Ratings</th>
                     <th>Review</td>
                </tr>



              @foreach($reviews as $key => $review)
                <tr>
                  <td>{{ ($key + 1) }}</td>
                  <td>{{ $review->user_name ??'' }}</td>
                  <td>{{ $review->ratings ??'' }} Star</td>
                  <td>{{ $review->review ??'' }}</td>
               </tr>
              @endforeach

            </table>
            </div>
         </div>
        </div>
      <!--   <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                   <table class="table table-responsive tabel-hover">
                    <h4>Question ask by patient</h4>
                    <tr>
                    <th>S.No</th>
                    <th>Speciality</th>
                    <th>Question</th>
                </tr>


              @foreach($questions as $key => $question)
                <tr>
                  <td>{{ ($key + 1) }}</td>
                   <td>{{ $question->specialityname->name ??'' }}</td>
                  <td>{{ $question->question ??'' }}</td>
               </tr>
              @endforeach -->

            </table>
            </div>
         </div>
        </div>
        <div class="container">
        <div class="row">
      <div class="col-md-12">
        <div class="nav-align-top mb-4">
          <ul class="nav nav-tabs nav-fill" role="tablist" style="margin:0px;">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true"><i class="tf-icons bx bx-home"></i> Today Appointment </button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false"><i class="tf-icons bx bx-user"></i> Old Appointment</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="false"><i class="tf-icons bx bx-message-square"></i> Upcomming Appointment</button>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
              <table  class="myTable1" id="myTable1">
                <thead>
                  <tr>
                    <th>ID </th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>TIme</th>
                    <th>Consult Type</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade  " id="navs-justified-profile" role="tabpanel">
              <table  class="myTable2" id="myTable2" style="width: 100% !important">
                <thead>
                  <tr>
                    <tr>
                      <th>ID </th>
                      <th>Patient</th>
                      <th>Date</th>
                      <th>TIme</th>
                      <th>Consult Type</th>
                    </tr>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
            <div class="tab-pane " id="navs-justified-messages" role="tabpanel">
              <table class="myTable3" id="myTable3" style="width: 100% !important">
                <thead>
                  <tr>
                    <tr>
                      <th>ID </th>
                      <th>Patient</th>
                      <th>Date</th>
                      <th>TIme</th>
                      <th>Consult Type</th>
                    </tr>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>
</div>
</div>
</div>
<style>
  table.dataTable thead th, table.dataTable thead td {
    padding: 10px 10px;
  }
</style>
<script>

  $( document ).ready(function() {

    var table = $('.myTable1').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{route('doctor.show', $doctor->id)}}/?type=today",
      columns: [
        {data: 'id', name: 'id'},
        // {data: 'doctor_id', name: 'doctor_id'},
        {data: 'patient_name', name: 'patient_name', orderable: false, searchable: true},
        {data: 'date', name: 'date'},
        {data: 'time', name: 'time', orderable: false, searchable: false},
        {data: 'consult_type', name: 'consult_type', orderable: false, searchable: true},
      ]

    });


    var table = $('.myTable2').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{route('doctor.show', $doctor->id)}}/?type=old",
      columns: [
        {data: 'id', name: 'id'},
        // {data: 'doctor_id', name: 'doctor_id'},
        {data: 'patient_name', name: 'patient_name', orderable: false, searchable: true},
        {data: 'date', name: 'date'},
        {data: 'time', name: 'time', orderable: false, searchable: false},
        {data: 'consult_type', name: 'consult_type', orderable: false, searchable: true},
      ]

    });

    var table = $('.myTable3').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{route('doctor.show', $doctor->id)}}/?type=upcoming",
      columns: [
        {data: 'id', name: 'id'},
        // {data: 'doctor_id', name: 'doctor_id'},
        {data: 'patient_name', name: 'patient_name', orderable: false, searchable: true},
        {data: 'date', name: 'date'},
        {data: 'time', name: 'time', orderable: false, searchable: false},
        {data: 'consult_type', name: 'consult_type', orderable: false, searchable: true},
      ]

    });

function myFunction() {
  document.getElementById("#is_online").innerHTML = "Hello World";
}


  });


</script>
 @endsection
