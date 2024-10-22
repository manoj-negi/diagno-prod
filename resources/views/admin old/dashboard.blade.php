@extends('layouts.adminDashboard') 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-6 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col">
                <h3 class="card-title text-primary" style=" margin: 20px;">Welcome Back Admin 🎉</h3>
              <div class="card-body">
              </div>
            </div>
            <div class="col-sm-4 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img
                  src="{{url('img/illustrations/man-with-laptop-light.png')}}"
                  class="img-fluid"
                  alt="View Badge User"
                  data-app-dark-img="{{url('illustrations/man-with-laptop-dark.png')}}"
                  data-app-light-img="{{url('illustrations/man-with-laptop-light.png')}}"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
       <div class="col-lg-3 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
           
            <div class=" ">
              <div class="card-body ">
                  
                   <span class="fw-semibold d-block ">Approved Status of doctor</span>
                   <h5>Pending Status</h5>
                <h3 class="card-title mb-2">{{$doctors ??''}}</h3>
               
              </div>
            </div>
          </div>
        </div>
      </div>

     <div class="col-lg-3 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
           
            <div class=" ">
              <div class="card-body ">
                  
                    <span class="fw-semibold d-block mb-1">Approved Status of doctor</span>
                   <h5>Rejected Status</h5>
                <h3 class="card-title mb-2">{{$rejected ??''}}</h3>
               
              </div>
            </div>
          </div>
        </div>
      </div>
        </div>
      <div class="col-sm-12 col-md-12 order-1">
        <div class="row">
          <div class="col-lg-3 col-md-12 col-3 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img
                      src="{{url('img/icons/doctor.jpg')}}"
                      alt="chart success"
                      class="rounded"
                    />
                  </div>
                  <div class="dropdown">
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                    </div>
                  </div>
                </div>
                <span class="fw-semibold d-block mb-1">Number of Doctor</span>
                <h3 class="card-title mb-2">{{$doctor ??''}}</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-12 col-3 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img
                      src="{{url('img/icons/patient.jpg')}}"
                      alt="Credit Card"
                      class="rounded"
                    />
                  </div>
                  <div class="dropdown">
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                    </div>
                  </div>
                </div>
                <span>Number of Patient</span>
                <h3 class="card-title text-nowrap mb-1">{{$patient ??''}}</h3>
              </div>
            </div>
          </div>
        <div class="col-lg-3 col-md-12 col-3 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img
                      src="{{url('img/icons/hospital.jpg')}}"
                      alt="Credit Card"
                      class="rounded"
                    />
                  </div>
                  <div class="dropdown">
            
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                   
                    </div>
                  </div>
                </div>
             
                <span class="card-title text-nowrap mb-1">Number of Hospital</span>
                   <h3 class="card-title text-nowrap mb-1">{{$hospitals ??''}}</h3>
               
              </div>
            </div>
          </div> <div class="col-lg-3 col-md-12 col-3 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img
                      src="{{url('img/icons/users.jpg')}}"
                      alt="Credit Card"
                      class="rounded"
                    />
                  </div>
                  <div class="dropdown">
                  
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                     
                    </div>
                  </div>
                </div>
                <span>All Users of Mebel</span>
              
                 <h3 class="card-title text-nowrap mb-1">{{$users ??''}}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Revenue -->
      <div class="row">
      <div class="col-8 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
          <div class="row row-bordered g-0">
            <div class="col-md-12">
              <h5 class="card-header m-0 me-2 pb-3">Recent Aapointments</h5>
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="justify-content: space-evenly;">
              <li class="nav-item" role="presentation">
               <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Today Appointment</button></li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Old Appointment</button></li>
                <li class="nav-item" role="presentation">
               <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Upcomeing Appointment</button>
             </li>
          </ul>


          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <table class="table table-responsive">
                    <tr>
                        
                        <th>Doctor Name</th>
                        <th>patient Name</th> 
                        <th>time</th>
                        <th>date</th>
                        <th>Consult Type</th>
                    </tr>
    
                    @foreach($todayAppointments as $key=> $todayAppointment)
                    <tr>
                        <td>{{$todayAppointment->doctor->name}}</td>
                        <td>{{$todayAppointment->patient->name}}</td>
                        <td>{{ucfirst($todayAppointment->time)}}</td>
                        <td>{{$todayAppointment->date}}</td>
                        <td>{{$todayAppointment->consult_type}}</td>

                       
                    </tr>
                    @endforeach
                  </table>
             
            </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <table class="table table-responsive">
                    <tr>

                       <th>Doctor Name</th>
                        <th>patient Name</th> 
                        <th>time</th>
                        <th>date</th>
                        <th>Consult Type</th>
                    </tr>
    
                    @foreach($oldAppointments as $key=> $oldAppointment)
                    <tr>
                        <td>{{$oldAppointment->doctor->name}}</td>
                        <td>{{$oldAppointment->patient->name}}</td>
                        <td>{{ucfirst($oldAppointment->time)}}</td>
                        <td>{{$oldAppointment->date}}</td>
                        <td>{{$oldAppointment->consult_type}}</td>

                       
                    </tr>
                    @endforeach
                  </table>
             
              </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                  <table class="table table-responsive">
                    <tr>
                        
                        <th>Doctor Name</th>
                        <th>patient Name</th> 
                        <th>time</th>
                        <th>date</th>
                        <th>Consult Type</th>
                    </tr>
    
                    @foreach($upComingAppointments as $key=> $upComingAppointment)
                    <tr>
                        <td>{{$upComingAppointment->doctor->name}}</td>
                        <td>{{$upComingAppointment->patient->name}}</td>
                        <td>{{ucfirst($upComingAppointment->time)}}</td>
                        <td>{{$upComingAppointment->date}}</td>
                        <td>{{$upComingAppointment->consult_type}}</td>
                       
                    </tr>
                    @endforeach
                            </table>
                          </div>
                     </div>
                    </div>   
                 </div>

              </div>


          </div>
           <div class="col-md-6 col-lg-4 order-1 mb-4 ml-5">
        <div class="card h-100">
          <div class="card-header">
           <!--  <ul class="nav nav-pills" role="tablist">
              <li class="nav-item">
               
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" role="tab">Doctor</button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" role="tab">Patient</button>
              </li>
            </ul> -->
          </div>
          <div class="card-body px-0">
            <div class="tab-content p-0">
              <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                <div class="d-flex p-4 pt-3">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="{{url('img/icons/unicons/wallet.png')}}" alt="User" />
                  </div>
                  <div>
                    <small class="text-muted d-block">Total Users</small>
                    <div class="d-flex align-items-center">
                      <h6 class="mb-0 me-1">{{$users}}</h6>
                     
                    </div>
                  </div>
                </div>
                <div id="incomeChart"></div>
                <div class="d-flex justify-content-center pt-4 gap-2">
                  <div class="flex-shrink-0">
                    <div id="expensesOfWeek"></div>
                  </div>
                  <div>
                    <p class="mb-n1 mt-1"></p>
                    <small class="text-muted"></small>
                  </div>
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
                <div class="row">
                  <div class="col-md-6">
                    <div class="card h-100">
                     <table class="table table-responsive">
                      <h3 style=" margin: 20px;">Recent Review</h3>
                    <tr>
                        
                        <th>Doctor Name</th>
                        <th>patient Name</th>
                        <th>Ratings</th>
                        <th>Review</th>
                        
                    </tr>
    
                    @foreach($reviews as $review)
                    <tr>
                       <td>{{$review->doctor->name}}</td>
                        <td>{{$review->patient->name}}</td>
                        <td>{{$review->ratings}} Star</td>
                        <td>{{$review->review}}</td>
                       

                       
                    </tr>
                    @endforeach
                  </table>
                      
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card h-100">
                     <table class="table table-responsive">
                      <h3 style=" margin: 20px;">Support</h3>
                    <tr>
                        
                        <th>Title</th>
                        <th>Issue</th>
                        <th>Remark</th>
                       
                        
                    </tr>
    
                    @foreach($supports as $support)
                    <tr>
                       <td>{{$support->title}}</td>
                        <td>{{$support->issue}}</td>
                        <td>{{$support->remark}} </td>
                        
                       

                       
                    </tr>
                    @endforeach
                  </table>
                      
                    </div>
                  </div>
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

@section('js')
<script>
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;
  // Income Chart - Area chart
  // --------------------------------------------------------------------
  const incomeChartEl = document.querySelector('#incomeChart'),
    incomeChartConfig = {
      series: [
        {
          data: ["<?php echo $yearly_data; ?>"]
        }
      ],
      chart: {
        height: 215,
        parentHeightOffset: 0,
        parentWidthOffset: 0,
        toolbar: {
          show: false
        },
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 2,
        curve: 'smooth'
      },
      legend: {
        show: false
      },
      markers: {
        size: 6,
        colors: 'transparent',
        strokeColors: 'transparent',
        strokeWidth: 4,
        discrete: [
          {
            fillColor: config.colors.white,
            seriesIndex: 0,
            dataPointIndex: 7,
            strokeColor: config.colors.primary,
            strokeWidth: 2,
            size: 6,
            radius: 8
          }
        ],
        hover: {
          size: 7
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: shadeColor,
          shadeIntensity: 0.6,
          opacityFrom: 0.5,
          opacityTo: 0.25,
          stops: [0, 95, 100]
        }
      },
      grid: {
        borderColor: borderColor,
        strokeDashArray: 3,
        padding: {
          top: -20,
          bottom: -8,
          left: -10,
          right: 8
        }
      },
      xaxis: {
        categories: ['','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug','Sept','Oct','Nov','Dec'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: true,
          style: {
            fontSize: '13px',
            colors: axisColor
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        },
        min: 10,
        max: 50,
        tickAmount: 4
      }
    };
  if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
    const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
    incomeChart.render();
  }
</script>
@endsection