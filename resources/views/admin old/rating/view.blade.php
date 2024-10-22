@extends('layouts.adminCommon')
@section('content')
<style>
    .rate {
        float: left;
        height: 46px;
        padding: 0 10px;
        }
        .rate:not(:checked) > input {
        position:absolute;
        display: none;
        }
        .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
        }
        .rated:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
        }
        .rate:not(:checked) > label:before {
        content: '★ ';
        }
        .rate > input:checked ~ label {
        color: #ffc700;
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
        color: #deb217;
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
        color: #c59b08;
        }
        .star-rating-complete{
           color: #c59b08;
        }
        .rating-container .form-control:hover, .rating-container .form-control:focus{
        background: #fff;
        border: 1px solid #ced4da;
        }
        .rating-container textarea:focus, .rating-container input:focus {
        color: #000;
        }
        .rated {
        float: left;
        height: 46px;
        padding: 0 10px;
        }
        .rated:not(:checked) > input {
        position:absolute;
        display: none;
        }
        .rated:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ffc700;
        }
        .rated:not(:checked) > label:before {
        content: '★ ';
        }
        .rated > input:checked ~ label {
        color: #ffc700;
        }
        .rated:not(:checked) > label:hover,
        .rated:not(:checked) > label:hover ~ label {
        color: #deb217;
        }
        .rated > input:checked + label:hover,
        .rated > input:checked + label:hover ~ label,
        .rated > input:checked ~ label:hover,
        .rated > input:checked ~ label:hover ~ label,
        .rated > label:hover ~ input:checked ~ label {
        color: #c59b08;
        }
        
</style>  
<form action="{{route('review.store')}}" method="POST">
  @csrf
       <p class="font-weight-bold ">Review</p>
            <div class="form-group row">
                  <input type="hidden" name="booking_id" value="">
                    <div class="col">
                      <div class="rate">
                             <input type="radio" id="star5" class="rate" name="star_rating" value="5"/>
                              <label for="star5" title="text">5 stars</label>
                               <input type="radio" checked id="star4" class="rate" name="star_rating" value="4"/>
                                <label for="star4" title="text">4 stars</label>
                                 <input type="radio" id="star3" class="rate" name="star_rating" value="3"/>
                                 <label for="star3" title="text">3 stars</label>
                                 <input type="radio" id="star2" class="rate" name="star_rating" value="2">
                                 <label for="star2" title="text">2 stars</label>
                                 <input type="radio" id="star1" class="rate" name="star_rating" value="1"/>
                                 <label for="star1" title="text">1 star</label>
                             </div>
                        </div>
                 </div>
                         <div class="form-group row mt-4">
                             <div class="col">
                              <textarea class="form-control" name="comments" rows="6 " placeholder="Comment" maxlength="200"></textarea>
                         </div>
                              </div>
                            <div class="mt-3 text-right">
                                                <button class="btn btn-sm py-2 px-3 btn-info">Submit
                                                </button>
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
  @endsection                               