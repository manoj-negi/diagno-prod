@extends('layouts.adminCommon')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- <div class="col-xl-2 col-lg-2 col-md-2 order-1 order-md-0">
        </div> -->
        <!-- Doctor Prifile -->
        <div class="col-xl-6 col-lg-6 col-md-6 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            <img class="img-fluid rounded my-4"
                                src="{{url('uploads/profile-imges').'/'.$data->profile_image ?? ''}}" height="400px"
                                width="125px" alt="User avatar">
                            <div class="user-info text-center">
                               
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap my-2 py-1">
                    </div>
                    <h5 class="pb-2 border-bottom mb-4">Patient Details</h5>
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
                            <li class="mb-3">
                                <span class="fw-bold me-2">Status:</span>
                                <span
                                    class="badge bg-label-success">{{$data->status=="0"?'In Active':'Active' ?? ''}}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Contact:</span>
                                <span>{{$data->number ?? ''}}</span>
                            </li>

                        </ul>
                        <div class="d-flex justify-content-center pt-0">
                            <a href="{{route('patient.index')}}" class="btn btn-warning btn-sm mb-3">Back</a>&nbsp;
                            <a href="{{route('patient.edit',$data->id)}}"
                                class="btn btn-primary btn-sm mb-3">Edit</a>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-xl-2 col-lg-2 col-md-2 order-1 order-md-0">
            </div> -->
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                   <h4>Appointments</h4>
                <table class="table table-responsive tabel-hover">

                <?php // echo json_encode($appointments);  die(); ?>
                <tr>
                    <th>S.no</th>
                     <th>date</th>
                     <th>time</th>
                    <th>Consult Type</th>
                  
                </tr>
                @if(count($appointments)>0)
                @php
                isset($_GET['paginate']) ? $paginate = $_GET['paginate'] : $paginate = 10;
                isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;

                $i = (($page-1)*$paginate)+1;
                @endphp
                @endif
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{$i++ ?? ''}}</td>
                    <td>{{$appointment->date}}</td>
                    <td>{{$appointment->time}}</td>
                    <td>{{$appointment->consult_type}}</td>        
                </tr>

                @endforeach
            </table>
            <div id="pagination">{{{ $appointments->links() }}}</div>
                </div>
            </div>
         </div>
          <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">
            <div class="card mb-4">
                <div class="card-body">
                     <h4>Reports</h4>
                <table class="table table-responsive tabel-hover">
                    <tr>
                    <th style="width: 5% !important;">S.no</th>
                     <th>Report</th>
                     <th>Date</th>
                     <th>Action</td>
                </tr>
                 @if(count($reports))

                @foreach($reports as $key => $report)
                <tr>
                    <td>{{ ($key + 1) }}</td>
                     <td>{{$report->report_image ??''}}</td> 
                     <td>{{date('d-m-y',strtotime($report->created_at)) ??''}}</td>      
                     <td>
                         <a href="{{url('img/reports',$report->report_image ??'')}}" download><button type="button" class="btn btn-primary btn-sm" >Download</button></a>
                     </td>
                @endforeach
                @endif
                </table>
            </div>
         </div>  
        </div>
    </div>
</div>
</div>
</div>
@endsection