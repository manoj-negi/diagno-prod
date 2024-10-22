@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-12 pt-5">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <h5 class="mb-4"><b>Profile Details</b></h5>
                            <table class="table">
                                <tbody>
                                    @can('dashboard_support_list')
                                    <tr>
                                        <th width="80">Lab:</th>
                                        <td>{{ isset($data->hospital->name) ? $data->hospital->name : 'No Lab Name
                                            Available' }}</td>
                                    </tr>
                                    @endcan
                                    <tr>
                                        <th width="80">Patient:</th>
                                        <td>{{ isset($data->patient->name) ? $data->patient->name : 'Patient Name Not
                                            Available' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="80">Patient Age:</th>
                                        <td>{{ isset($data->patient->age) ? $data->patient->age : 'Patient age Not
                                            available' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="80">Patient Number:</th>
                                        <td>{{ isset($data->patient->number) ? $data->patient->number : 'Patient age Not
                                            available' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="80">Gender :</th>
                                        <td>{{ isset($data->patient->sex) ? $data->patient->sex : 'Patient Gender Not
                                            available' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="80">Patient Address:</th>
                                        <td>{{ isset($data->address->house_no) ? $data->address->house_no : '' }},
                                            {{ isset($data->address->address) ? $data->address->address : '' }},
                                            {{ isset($data->address->near_landmark) ? $data->address->near_landmark : ''
                                            }},
                                            {{ isset($data->address->area) ? $data->address->area : '' }},
                                            {{ isset($data->address->pin_code) ? $data->address->pin_code : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="80">Status :</th>
                                        <td>{{ $data->status }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="mb-3 col-md-6">
                            <h5 class="mb-4"><b>Other Details</b></h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="80">Date: </th>
                                            <td>{{ date('d F Y',strtotime($data->date)) }} {{ date('h:i
                                                A',strtotime($data->time))}}</td>
                                        </tr>
                                        <tr>
                                            <th width="80">Booking Date: </th>
                                            <td>{{ date('d F Y h:i A',strtotime($data->created_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <th width="80">Report File: </th>
                                            <td>
                                                <div class="show_image_round">
                                                    @if (!empty($data->report_image))
                                                    <div class="download_button mt-2">
                                                        <a href="{{ url('uploads/appointment/', $data->report_image ?? '') }}"
                                                            download>
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm">Download</button>
                                                        </a>
                                                    </div>
                                                    @else
                                                    <p>Report Not Found</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="80">Invoice: </th>
                                            <td>
                                                <div class="show_image_round">
                                                    @if (!empty($data->invoice))
                                                    <div class="download_button mt-2">
                                                        <a href="{{ url($data->invoice) }}"
                                                            download>
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm">Download</button>
                                                        </a>
                                                    </div>
                                                    @else
                                                    <p>Invoice Not Found</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h5 class="mb-4 mt-3"><b>Payment Details</b></h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="80">Booking Amount </th>
                                            <td>{{ number_format($data->booking_amount ?? 0,2) }}</td>
                                        </tr>
                                        <tr>
                                            <th width="80">Payment Mode: </th>
                                            <td>{{ $data->payment_mode == 'cash' ? 'Pay Later' :
                                                ucfirst($data->payment_mode) ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th width="80">Transaction Status: </th>
                                            <td> {{ucfirst($data->payment_status ?? 'Pending' )}}</td>
                                        </tr>
                                        <tr>
                                            <th width="80">Transaction ID: </th>
                                            <td>@if($data->payment_mode == 'online') {{ ucfirst($data->transaction_id)
                                                ?? 'Payment Not Completed Yet' }} @else <span class="text-danger">
                                                    Payment Not Completed Yet
                                                </span> @endif</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3 col-md-12">
                            <h5 class="mb-4 mt-3"><b>Medical Details</b></h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="80">Test Type</th>
                                            <td>{{ ucfirst($data->test_type) }}</td>
                                        </tr>

                                        <tr>
                                            <th width="80">Booking items</th>
                                            <td>
                                                @if(isset($data->packages))
                                                @foreach($data->packages as $item)
                                                {{$item['package_name'] ?? ''}}
                                                @if (!$loop->last),@endif
                                                @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="80">Need Hard Copy of Report: </th>
                                            <td>{{ $data->report_hard_copy ? 'Yes' : 'No' }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('appointments.index') }}"
                                class="btn btn-warning btn-sm mb-3 mt-3">Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <h5 class="mb-4"><b>Profile Details</b></h5>
                            <table class="table table">
                                <tr>
                                    <th style="width: 10px!important;">S.No</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                </tr>
                                @forelse($appoinment as $key => $appointment)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $appointment->status }}</td>
                                    <td>{{ $appointment->created_at->format('H:i:s') }}</td>
                                    <td>{{ $appointment->created_at->toDateString() }}</td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Booking Tracking available</td>
                                </tr>
                                @endforelse
                            </table>
                            <div class="d-flex justify-content-end mt-4">
                                {{ $appoinment->links() }}
                            </div>

                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

</div>
@endsection