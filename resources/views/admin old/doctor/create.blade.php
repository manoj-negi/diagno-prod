@extends('layouts.adminCommon')
@section('content')
<div class="container mt-6">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body">
                <form action="{{route('doctor.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $userData->id ?? ''}}" />
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $userData->name ?? '')}}"  required/>
                            @error('name')
                            <div class="validationclass text-danger pt-4">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="{{old('email', $userData->email ?? '')}}"  required/>
                            @error('email')
                            <div class="validationclass text-danger pt-4">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" name="number" min="0" class="form-control" @error('number') invalid @enderror
                                value="{{old('number', $userData->number ?? '')}}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                                .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  maxlength="10" required />
                            @error('number')
                            <div class="alert text-danger p-0 mb-4 pt-2">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image</label>
                            <input type="file" name="profile_image" multiple class="form-control  " />
                            @if(isset($userData) && !empty($userData->image))
                            <img src="{{url('uploads/profile-imges'.'/'.$userData->profile_image)}}" width="50px"
                                height="50px" />
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" @error('address') invalid @enderror
                                value="{{ old('address', $userData->address ?? '')}}" />
                            @error('address')
                            <div class="alert text-danger p-0 mb-4 pt-4">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Experience<span class="text-danger">*</span></label>
                            <input type="text" name="experience" class="form-control " @error('experience') invalid
                                @enderror value="{{old('experience',  $userData['experience'] ?? '')}}" required/>
                            @error('experience')
                            <div class="validationclass text-danger pt-4">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Educations<span class="text-danger">*</span></label>
                            <select name="education[]" class="multiple form-control mb-2  mr-sm-2 select2 form-select"
                            multiple="multiple" id="">
                            @foreach($education as $key => $data)
                            <option value="{{$key}}"{{(in_array($key, old('education', [])) || isset($userData) && $userData->educations->contains($key)) ? 'selected' :''}}>{{$data}}</option>
                            @endforeach
                        </select>
                         @error('education')
                           <div class="alert text-danger p-0 mb-4 pt-2">{{$message}}</div>
                         @enderror
                    </div>
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Bio</label>
                            <input type="text" name="doctor_bio" class="form-control" @error('bio') invalid @enderror
                                value="{{old('doctor_bio', $userData->doctor_bio ?? '')}}"/>
                            @error('bio')
                            <div class="alert text-danger p-0 mb-4 pt-4">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Speciality<span class="text-danger">*</span></label>
                            <select name="speciality[]" class="multiple form-control mb-2  mr-sm-2 select2 form-select "
                                multiple="multiple" id="">
                                @foreach($speciality as $key => $result)
                                <option value="{{$key}}" {{(in_array($key, old('speciality', [])) || isset($userData) && $userData->speciality->contains($key)) ? 'selected' :''}}>{{$result}}</option>
                                @endforeach
                            </select>
                              @error('speciality')
                                <div class="alert text-danger p-0 mb-4 pt-2">{{$message}}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Hospital Name<span class="text-danger">*</span></label>
                            <select name="hospital[]" class="multiple form-control mb-2  mr-sm-2 select2 "
                            multiple="multiple" id="">
                                <option value="">Select Name</option>
                                @foreach($hospitals as $key => $data)
                                <option value="{{$key}}"{{(in_array($key, old('hospital', [])) || isset($userData) && $userData->hospitals->contains($key)) ? 'selected' :''}}>{{$data}}
                                    </option>
                                    @endforeach
                            </select>
                                @error('hospital')
                                <div class="alert text-danger p-0 mb-4 pt-2">{{$message}}</div>
                                @enderror
                        </div>
                    </div>
                       <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select text-muted mr-1">
                                <option value="1" {{isset($userData) && $userData->status=="1" ? "selected" : '' }}>
                                    &nbsp;&nbsp;Active
                                </option>
                                <option value="0" {{isset($userData) && $userData->status=="0" ? "selected" : ''}}>
                                    &nbsp;&nbsp;De-Active
                                </option>
                            </select>
                        </div>
                         <div class="col-md-6 pt-3">
                            <label class="form-label">By Trun Time</label>
                            <select name="by_turn_time" class="form-select text-muted mr-1">
                                <option class="text-dark" value="0" {{isset($userData) && $userData->by_trun=="0" ? "selected" : ''}}>by Trun
                                 </option>
                                <option class="text-dark" value="1" {{isset($userData) && $userData->is_time=="1" ? "selected" : ''}}>Is Time
                                 </option>
                            </select>
                         </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Is virtual</label>
                             <select name="is_virtual" class="form-select text-muted mr-1">
                                <option class="text-dark" value="1" {{isset($userData) && $userData->is_virtual=="1" ? "selected" : ''}}>&nbsp;&nbsp;Is Virtual
                                 </option>
                                <option class="text-dark" value="0" {{isset($userData) && $userData->is_physical=="0" ? "selected" : ''}}>&nbsp;&nbsp;Is Physical
                                 </option>
                            </select>
                        </div>
                            <div class="col-md-6 pt-3">
                                <label class="form-label">Approved</label>
                                 <select name="is_approved" class="form-select text-muted mr-1">
                                    <option class="text-dark" value="pending" >&nbsp;&nbsp;Pending
                                     </option>
                                      <option class="text-dark" value="Approved" >&nbsp;&nbsp;Approved
                                     </option>
                                    <option class="text-dark" value="rejected" >&nbsp;&nbsp;Rejected
                                     </option>
                                 </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 pt-3">
                                <label for="" class="form-label mt-2">Exequatur Number<span class="text-danger">*</span></label>
                                <input type="text" min="0" name="exequatur_number" class="form-control exequatur_number"
                                    value="{{old('exequatur_number', $userData->exequatur_number ?? '')}}"  maxlength="8" required >
                                    @error('exequatur_number')
                                     <div class="validationclass text-danger pt-4">{{$message}}</div>
                                    @enderror
                            </div>
                        </div>
                        {{-- <div class="row">
                        <div class="col-md-2">
                        <button class="btn btn-large btn-success btn-sm add mt-4" type="button">Add</button>
                        </div>
                        </div> --}}
                       {{-- <div class="row">
                         <div id="attributes">
                            <div class="attr">
                                 <label for="" class="form-label mt-2">Question</label>
                                <input name="question[]" id="" type="text" placeholder="Name" class="required-entry form-control mt-2">
                                 @error('name')
                                     <div class="validationclass text-danger pt-4">{{$message}}</div>
                                    @enderror
                               
                                <button class="btn btn-danger remove" type="button">Remove</button> 
                         </div>

                      </div>

                        </div> --}}
                       </div>
                       


                         <!--  -->


                        <div class="container ">
                            <label class="mt-2 form-label mt-3">Doctor Availability</label>
                    <div class="row">
                        <div class="col-md-6"><label class="mt-2 form-label"> Opening Time </label>
                        </div>
                        <div class="col-md-6"><label class="mt-2 form-label text-align center"> Closeing time</label>
                        </div>
                                
                        <div class="col-md-10 pt-3">
                            <div class="form-group">
                                @php
                                $weekdays=['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                'Saturday'];
                                @endphp

                                @if(isset($userData) && (count($userData->Availability)>0))

                                @foreach($userData->Availability as $i => $value)

                                <div class="row">
                                    <div class="form-group col-md-2">

                                        <div class="form-check">
                                            <label class="form-check-label text-muted">
                                               <input class="form-check-input " type="checkbox" name="weekday[{{$value->week_day}}]" {{ $value->status == 1 ? 'checked' : '' }} value="1">  {{($weekdays[$i])}}
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <input type="time" name="start_time[{{$value->week_day}}]" class="form-control"
                                            value="{{$value->start_time ??''}}">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <input type="time" name="end_time[{{$value->week_day}}]" class="form-control"
                                            value="{{$value->end_time ??''}}">
                                    </div>
                                </div>
                                @endforeach
                                @else

                                @foreach ($weekdays as $key=>$value)

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted">{{$value}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="time" name="start_time[{{$key}}]" class="form-control"
                                            value="{{$value->start_time ??''}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="time" name="end_time[{{$key}}]" class="form-control"
                                            value="{{$value->end_time ??''}}">
                                    </div>
                                </div>

                                @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-3 ">
                                    <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm mb-3">Back</a>
                                    <button type="submit" class="btn btn-primary btn-sm mb-3"
                                        value="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    $('.multiple').select2({

        placeholder: "Select",
        allowClear: true
    });
    $(document).on('keyup', '.exequatur_number', function () {
        $value = $(this).val();
      
        if (($value !== "" && /^[0-9 -]*$/.test($value) == false) ) {
            $(this).val('');
        }else{
            if($value.length == 4){
                $(this).val($value+'-');
            }
        }
    
    });
        var row = $(".attr");

    function addRow() {
      row.clone(true, true).appendTo("#attributes");
    }

    function removeRow(button) {
      button.closest("div.attr").remove();
    }

    $('#attributes .attr:first-child').find('.remove').hide();

    /* Doc ready */
    $(".add").on('click', function () {
      addRow();  
      if($("#attributes .attr").length > 1) {
        //alert("Can't remove row.");
        $(".remove").show();
      }
    });
    $(".remove").on('click', function () {
      if($("#attributes .attr").size() == 1) {
        //alert("Can't remove row.");
        $(".remove").hide();
      } else {
        removeRow($(this));
        
        if($("#attributes .attr").size() == 1) {
            $(".remove").hide();
        }
        
      }
});


});
</script>
</body>


@endsection
