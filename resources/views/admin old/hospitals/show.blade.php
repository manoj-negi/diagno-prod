@extends('layouts.adminCommon')
@section('content')
<div class="container">
        <div class="row pb-2">
            <div class="col-md-12 text-right mt-2">
                <a href="{{route('hospitals.index')}}" class="btn btn-primary btn-sm">Back</a>
            </div>
        <div class="col-md-12 pt-2">
            <div class="card">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6 ">
                        <div class="paging-section justify-content-end">
                        <form class="index-form">
                                <div class="search_bar d-flex">
                                        <select class="form-select text-muted mr-1"
                                            onchange="document.querySelector('.index-form').submit()" id="filter"
                                            name="filter">
                                            <option selected="true" disabled="disabled">Select Status
                                            </option>
                                            <option class="text-dark" value="1"
                                                {{request()->get('filter')=='1'?'selected':''}}>Active
                                            </option>
                                            <option class="text-dark" value="0"
                                                {{request()->get('filter')=='0'?'selected':''}}>De-Active
                                            </option>
                                        </select>
                                           <select class="form-select text-muted mr-1" onchange="document.querySelector('.index-form').submit()" id="filter" name="app_filter">
                                        <option selected="true" disabled="disabled" >{{__('Approvel Status')}}</option>
                                        <option class="text-dark" value="approved"{{request()->get('app_filter')=='approved'?'selected':''}}>Approved</option>
                                        <option class="text-dark" value="pending" {{request()->get('app_filter')=='pending'?'selected':''}}>Pending</option>
                                        <option class="text-dark" value="rejected" {{request()->get('app_filter')=='rejected'?'selected':''}}>Rejected</option>
                                    </select >
                                        <input type="" id="search" name="search" placeholder="Search"
                                            class="form-control"
                                            value="{{(request()->get('search') != null)? request()->get('search'):''}}"
                                            placehoder="Search" />
                                        <button type="submit" class="form-control src-btn"><i class="fa fa-search"
                                                aria-hidden="true"></i></button>
                                        <a class="form-control src-btn" href="{{request()->url()}}"><i
                                            class="fa fa-rotate-left"></i></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive">
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>email</th>
                            <th>number</th>
                            <th>Approvel status</th>
                            <th>status</th>

                        </tr>
                       

                        @foreach($result as $key=> $value)
                        <tr>
                            <td>{{$result->firstItem() + $key ??'' }}</td>
                            <td>{{$value->getDoctorDetails['name']}}</td>
                            <td>{{$value->getDoctorDetails['email']}}</td>
                            <td>{{$value->getDoctorDetails['number']}}</td>
                            <td><span class="badge {{ ['approved' => 'badge-success', 'pending' => 'badge-info', 'rejected' => 'badge-danger'][$value->getDoctorDetails['is_approved']] }}" >{{$value->getDoctorDetails['is_approved']}}</span></td>
                            <td><span
                                    class="badge badgess">{{$value->getDoctorDetails['status']=="1"?'Active':'In Active'}}</span>
                            </td>
                        </tr>
                        @endforeach
                        
                    </table>
                    <div id="pagination">{{{ $result->links() }}}</div>
                </div>

            </div>
        </div>
    </div>

    <!--Script Part-->
    <script>
    $(document).ready(function() {
        $('#pagination').on('change', function() {

            var $form = $(this).closest('form');
            $form.find('input[type=submit]').click();
            console.log($form);

        });

    });
    </script>
    @endsection