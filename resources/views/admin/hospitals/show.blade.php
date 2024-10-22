@extends('layouts.adminCommon')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
       
        <div class="col-xl-6 col-lg-6 col-md-6 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                <div class="user-avatar-section">
    <div class="d-flex align-items-center flex-column">
        @if(!empty($data->hospital_logo))
            <img src="{{ url('uploads/hospital/'.$data->hospital_logo) }}" style="box-shadow: 0px 0px 9px -2px #000000; width: 150px; border-radius: 100%; height: 150px;">
        @else
        <img src="{{url('uploads/hospital/49912demo.jpg')}}" alt="" style=" box-shadow: 0px 0px 9px -2px #000000;width: 150px;border-radius: 100%;height: 150px;">
        @endif
        <div class="user-info text-center">
         
        </div>
    </div>
</div>

                    <div class="d-flex justify-content-around flex-wrap my-2 py-1">
                    </div>
                    <h5 class="pb-2 border-bottom mb-4">Hospital Details</h5>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <span class="fw-bold me-2">name:</span>
                                <span>{{$data->name ?? ''}}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Email:</span>
                                <span>{{$data->email ??''}}</span>
                            </li>
                        
                            <!-- <li class="mb-3">
                                <span class="fw-bold me-2">Contact:</span>
                                <span>{{$data->number ?? ''}}</span>
                            </li> -->

                        </ul>
                        <div class="d-flex justify-content-center pt-0">
                            <a href="{{route('hospitals.index')}}" class="btn btn-warning btn-sm mb-3">Back</a>&nbsp;
                            <!-- <button  type="button" class="btn btn-info btn-sm mb-3"  data-bs-toggle="modal" data-bs-target="#presetquestion">Preset Question Answers</button>&nbsp; -->
                            <a href="{{route('hospitals.edit',$data->id)}}"
                                class="btn btn-primary btn-sm mb-3">Edit</a>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="col-xl-6 col-lg-6 col-md-6 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Appointments</h4>
                    <table class="table table">
                        <tr>
                            <th style="width: 5% !important;">S.No</th>
                            <th>date</th>
                            <th>time</th>
                            <th>Consult Type</th>
                        </tr>
                        @if(count($appointments) > 0)
                            @php
                                isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
                                isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
                                $i = (($page - 1) * $paginate) + 1;
                            @endphp
                        @endif
        
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $i++ ?? '' }}</td>
                                <td>{{ $appointment->date ?? '---' }}</td>
                                <td>{{ $appointment->time ?? '---' }}</td>
                                <td>{{ $appointment->consult_type ?? '---' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="4">No appointments available</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Doctor</h4>
                    <table class="table table">
                        <tr>
                            <th style="width: 10px!important;">S.No</th>
                            <th>hospital</th>
                            <th>doctor name</th>
                            <th>doctor age</th>
                            <th>doctor category</th>
                        </tr>
                        @if(count($doctor) > 0)
                            @php
                                isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
                                isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
                                $i = (($page - 1) * $paginate) + 1;
                            @endphp
                        @endif
        
                        @forelse($doctor as $appointment)
                            <tr>
                                <td>{{ $i++ ?? '' }}</td>
                                <td>{{ $appointment->hospital_id }}</td>
                                <td>{{ $appointment->doctor_name ?? '---' }}</td>
                                <td>{{ $appointment->doctor_age ?? '---' }}</td>
                                <td>{{ $appointment->doctor_category ?? '---' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="5">No Doctors available</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
</div>
</div>
@endsection
