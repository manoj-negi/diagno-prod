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
                        {{-- <div class="col-md-6 pt-3">
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
                        </div> --}}
                        <div class="col-md-12 pt-3">
                            <label class="form-label">Bio</label>
                            <textarea name="doctor_bio" class="form-control" @error('bio') invalid @enderror rows="5">{{old('doctor_bio', $userData->doctor_bio ?? '')}}</textarea>
                            <!-- <input type="text" name="doctor_bio" class="form-control" @error('bio') invalid @enderror
                                value="{{old('doctor_bio', $userData->doctor_bio ?? '')}}"/> -->
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
                            <label for="" class="form-label mt-2">Exequatur Number<span class="text-danger">*</span></label>
                            <input type="text" min="0" name="exequatur_number" class="form-control exequatur_number"
                                value="{{old('exequatur_number', $userData->exequatur_number ?? '')}}"  maxlength="8" required >
                                @error('exequatur_number')
                                    <div class="validationclass text-danger pt-4">{{$message}}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select text-muted mr-1">
                                <option value="1" {{isset($userData) && $userData->status=="1" ? "selected" : '' }}>Active</option>
                                <option value="0" {{isset($userData) && $userData->status=="0" ? "selected" : ''}}>De-Active</option>
                            </select>
                        </div>
                         <div class="col-md-6 pt-3">
                            <label class="form-label">By Trun Time</label>
                            <select name="by_turn_time" class="form-select text-muted mr-1">
                                <option class="text-dark" value="0" {{isset($userData) && $userData->by_trun=="0" ? "selected" : ''}}>by Trun</option>
                                <option class="text-dark" value="1" {{isset($userData) && $userData->is_time=="1" ? "selected" : ''}}>Is Time</option>
                            </select>
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Is virtual</label>
                             <select name="is_virtual" class="form-select text-muted mr-1">
                                <option class="text-dark" value="1" {{isset($userData) && $userData->is_virtual=="1" ? "selected" : ''}}>Is Virtual</option>
                                <option class="text-dark" value="0" {{isset($userData) && $userData->is_physical=="0" ? "selected" : ''}}>Is Physical</option>
                            </select>
                        </div>
                        <div class="col-md-6 pt-3">
                            <label class="form-label">Approved</label>
                                <select name="is_approved" class="form-select text-muted mr-1">
                                <option class="text-dark" value="pending" {{(isset($userData) && $userData->is_approved=="pending") ? "selected" : ''}} >Pending</option>
                                    <option class="text-dark" value="Approved" {{ (isset($userData) && $userData->is_approved=="approved") ? "selected" : ''}} >Approved</option>
                                <option class="text-dark" value="rejected" {{(isset($userData) && $userData->is_approved=="rejected") ? "selected" : ''}} >Rejected</option>
                                </select>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-2">
                        <button class="btn btn-large btn-success btn-sm add mt-4" type="button">Add</button>
                        </div>
                    </div> --}}
                    {{-- 
                    <div class="row">
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
                    </div> 
                    --}}
                    <br><br>
                    <div class="row">
                        <label class="mt-2 form-label mt-3"><h4 class="">Doctor Availability</h4></label>
                        <div class="col-md-12 pt-3 pb-4">
                            <label class="form-label">Hospital Name<span class="text-danger">*</span></label>
                            <select name="hospital[]" class="multiple form-control mb-2  mr-sm-2 select2 " multiple="multiple" id="">
                                @foreach($hospitals as $key => $data)
                                <option value="{{$key}}" {{ (in_array($key, old("hospital", [])) || isset($userData) && $userData->hospitals->contains($key)) ? 'selected' :''}}>{{$data}}</option>
                                @endforeach
                            </select>
                            @error('hospital')
                            <div class="alert text-danger p-0 mb-4 pt-2">{{$message}}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="col-md-2">
                            <label class="mt-2 form-label"> Hospital Name </label>
                        </div>
                        <div class="col-md-2">
                            <label class="mt-2 form-label">Week</label>
                        </div>
                        <div class="col-md-4"><label class="mt-2 form-label"> Opening Time </label></div>
                        <div class="col-md-4"><label class="mt-2 form-label text-align center"> Closing time</label></div>

                        <div class="col-md-10 pt-3">
                            <div class="form-group">
                                @php
                                $weekdays=['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                @endphp

                                @if(isset($userData) && (count($userData->Availability)>0))

                                    @foreach($userData->Availability as $i => $value)

                                    <div class="row justify-content-end">
                                        @if ($i==0||$i%7==0)
                                        <div class="col-2"><h5>{{$value->hospitals->name ?? '' }}</h5></div>
                                        <div class="col-2"></div>
                                        <div class="col-4"></div>
                                        <div class="col-4"></div>
                                        <input type="hidden" value="{{$value->hospitals->id ?? ''}}" name="hospital_id[]"/>
                                        @endif

                                        <div class="form-group col-md-2">
                                            <div class="form-check">
                                                <label class="form-check-label text-muted">
                                                    <input class="form-check-input " type="checkbox" name="weekday[{{$value->hospitals->id}}][{{$value->week_day}}]" {{ $value->status == 1 ? 'checked' : '' }} value="1">  {{($weekdays[$value->week_day-1])}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="time" name="start_time[{{$value->hospitals->id}}][{{$value->week_day}}]" class="form-control" value="{{$value->start_time ??''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="time" name="end_time[{{$value->hospitals->id}}][{{$value->week_day}}]" class="form-control" value="{{$value->end_time ??''}}">
                                        </div>
                                        
                                    </div>
                                    @endforeach
                                @else
                                    @foreach ($weekdays as $key=>$value)
                                    <div class="row justify-content-end">
                                        @if ($key==0||$key%7==0)
                                        <div class="col-2"><h5 class="hospital_name">No Hospital</h5></div>
                                        <div class="col-2"></div>
                                        <div class="col-4"></div>
                                        <div class="col-4"></div>
                                        @endif
                                        <div class="form-group col-md-2">
                                            <label class="form-check-label text-muted pl-4 ml-2">{{$value}}</label>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="time" name="start_time[{{$key}}]" class="form-control" value="{{$value->start_time ??''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="time" name="end_time[{{$key}}]" class="form-control" value="{{$value->end_time ??''}}">
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 ">
                            <a href="{{ url()->previous() }}" class="btn btn-warning btn-sm mb-3">Back</a>
                            <button type="submit" class="btn btn-primary btn-sm mb-3" value="submit">Submit</button>
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

$('.select2').on('change', function() {
      var data = $(".select2 option:selected").text();
      console.log('data: ', data);
    })

});
</script>
</body>


@endsection
