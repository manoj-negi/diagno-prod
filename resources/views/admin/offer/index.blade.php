@extends('layouts.adminCommon')

@section('content')
<style>
    .offer_image img {
        width: 47px;
        height: 47px;
    }

    .main_dates {
        margin-top: 14px !important;
    }

    /* Adjust table and table cell styles */
    table.table {
        width: 100%;
        border-collapse: collapse;
    }

    table.table th,
    table.table td {
        padding: 8px;
        text-align: left;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        table.table th,
        table.table td {
            font-size: 14px;
        }
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-primary btn-sm mt-3" href="{{ route('offer.create') }}">Create</a><br><br>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="card">
            <div class="col-md-12">
                <form>
                    <div class="row">
                        <div class="col-md-5">
                            <!-- Your filter inputs -->
                        </div>
                        <div class="col-md-2 mt-2 main_dates">
                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Start Date" class="form-control" value="{{ app('request')->input('start_date') }}" name="start_date" fdprocessedid="t3ftiu">
                        </div>
                        <div class="col-md-2 mt-2 main_dates">
                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="End Date" class="form-control" value="{{ app('request')->input('end_date') }}" name="end_date" fdprocessedid="t3ftiu">
                        </div>
                        <div class="col-md-3">
                            <div class="paging-section">
                                <div class="search_bar d-flex">
                                    <input type="text" id="search" name="search" class="form-control" value="{{ request()->get('search', '') }}" placeholder="Search">&nbsp;
                                    <button type="submit" class="form-control src-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <a class="form-control src-btn" href="{{ route('offer.index') }}"><i class="fa fa-rotate-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px!important;">S.No</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Discount (%)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Terms and Conditions</th>
                            <th>Status</th>
                            <th>Offer Code</th>
                            <th>Offer Type</th>
                            <th>Max Discount</th>
                            <th>Min Purchase Amount</th>
                            <th>Applicable To</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data) > 0)
                        @foreach($data as $index => $offer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $offer->title }}</td>
                            <td>{{ $offer->description }}</td>
                            <td>{{ $offer->discount_percentage }}</td>
                            <td>{{ \Carbon\Carbon::parse($offer->start_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($offer->end_date)->format('Y-m-d') }}</td>
                            <td>{{ $offer->terms_and_conditions }}</td>
                            <td>{{ ucfirst($offer->status) }}</td>
                            <td>{{ $offer->offer_code }}</td>
                            <td>{{ ucfirst($offer->offer_type) }}</td>
                            <td>{{ $offer->maximum_discount }}</td>
                            <td>{{ $offer->minimum_purchase_amount }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $offer->applicable_to)) }}</td>
                            <!-- <td>
                                <a href="{{ route('offer.edit', $offer->offer_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <form action="{{ route('offer.destroy', $offer->offer_id) }}" method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm show_confirm" onclick="return confirm('Are you sure you want to delete this offer?')"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </form>
                            </td> -->
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="14" class="text-center">No offers found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div id="pagination">{{ $data->links() }}</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#pagination').on('change', function() {
            var $form = $(this).closest('form');
            $form.find('input[type=submit]').click();
        });
    });
</script>
@endsection
