@extends('layouts.adminCommon')
@section('content')

<style>
    .alg-self-center{
        align-self: center;
    }
</style>

<div class="container">
    <div class="row justify-content">
            <div class="col-md-12 pt-3">
            <div class="card">
                <div class="card-body">
                <form action="{{route('static-pages.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$datas->id ?? ''}}" />
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <!-- <h3 class="text-center">Section 1</h3> -->
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', isset($datas) && isset($datas->title) ? $datas->title : '') }}" class="form-control" required />
                    </div>
                    @error('title')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div> 
                    @if (isset($datas) && $datas->id==4)
                    
                        
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label><b>Top Content</b></label>
                        <textarea name="top_content" class="form-control">{{ old('top_content', isset($dataresult) && isset($dataresult->top_content) ? $dataresult->top_content : '') }}</textarea>
                    </div>
                    @error('top_content')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                   
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Top Content Banner Image</label>
                    <input type="file" name="top_content_banner_image" value="{{ old('top_content_banner_image', isset($dataresult) && isset($dataresult->top_content_banner_image) ? $dataresult->top_content_banner_image : '') }}" class="form-control" />
                    </div>
                    @error('top_content_banner_image')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->top_content_banner_image))
                    <img src="{{url($dataresult->top_content_banner_image)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    </div>
                    <div class="row mt-5">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <h3 class="text-center">Section 2</h3>
                    <label class=""><b> Middle Content</b></label>
                        <textarea name="middle_content" class="form-control">{{ old('middle_content', isset($dataresult) && isset($dataresult->middle_content) ? $dataresult->middle_content : '') }}</textarea>
                    </div>
                    @error('middle_content')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Middle Content Banner Image</label>
                    <input type="file" name="middle_content_banner_image" value="{{ old('middle_content_banner_image', isset($dataresult) && isset($dataresult->middle_content_banner_image) ? $dataresult->middle_content_banner_image : '') }}" class="form-control" />
                    </div>
                    @error('middle_content_banner_image')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->middle_content_banner_image))
                    <img src="{{url($dataresult->middle_content_banner_image)}}" width="80px" height="80px"/>
                    @endif
                    
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Service Icon Image Left</label>
                    <input type="file" name="service_icon_image_left" value="{{ old('service_icon_image_left', isset($dataresult) && isset($dataresult->service_icon_image_left) ? $dataresult->service_icon_image_left : '') }}" class="form-control" />
                    </div>
                    @error('service_icon_image_left')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->service_icon_image_left))
                    <img src="{{url($dataresult->service_icon_image_left)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Service Icon Image Middle</label>
                    <input type="file" name="service_icon_image_middle" value="{{ old('service_icon_image_middle', isset($dataresult) && isset($dataresult->service_icon_image_middle) ? $dataresult->service_icon_image_middle : '') }}" class="form-control" />
                    </div>
                    @error('service_icon_image_middle')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->service_icon_image_middle))
                    <img src="{{url($dataresult->service_icon_image_middle)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Service Icon Image Right</label>
                    <input type="file" name="service_icon_image_right" value="{{ old('service_icon_image_right', isset($dataresult) && isset($dataresult->service_icon_image_right) ? $dataresult->service_icon_image_right : '') }}" class="form-control" />
                    </div>
                    @error('service_icon_image_right')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->service_icon_image_right))
                    <img src="{{url($dataresult->service_icon_image_right)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    </div>
                    <h3 class="text-center my-4">Section 3</h3>  
                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Service Content Left</label>
                        <textarea name="service_content_left" class="form-control">{{ old('service_content_left', isset($dataresult) && isset($dataresult->service_content_left) ? $dataresult->service_content_left : '') }}</textarea>
                    </div>
                    @error('service_content_left')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Service content Right</label>
                        <textarea name="service_content_right" class="form-control">{{ old('service_content_right', isset($dataresult) && isset($dataresult->service_content_right) ? $dataresult->service_content_right : '') }}</textarea>
                    </div>
                    @error('service_content_right')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Service Content Middle</label>
                        <textarea name="service_content_middle" class="form-control">{{ old('service_content_middle', isset($dataresult) && isset($dataresult->service_content_middle) ? $dataresult->service_content_middle : '') }}</textarea>
                    </div>
                    @error('service_content_middle')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                    <h3 class="text-center my-3">Section 4</h3>
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Heading</label>
                        <textarea name="support_heading" class="form-control">{{ old('support_heading', isset($dataresult) && isset($dataresult->support_heading) ? $dataresult->support_heading : '') }}</textarea>
                    </div>
                    @error('support_heading')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Image First</label>
                    <input type="file" name="support_image_first" value="{{ old('support_image_first', isset($dataresult) && isset($dataresult->support_image_first) ? $dataresult->support_image_first : '') }}" class="form-control" />
                    </div>
                    @error('support_image_first')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->support_image_first))
                    <img src="{{url($dataresult->support_image_first)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    </div>
                    <div class="row">
                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Content First</label>
                        <textarea name="support_content_first" class="form-control">{{ old('support_content_first', isset($dataresult) && isset($dataresult->support_content_first) ? $dataresult->support_content_first : '') }}</textarea>
                    </div>
                    @error('support_content_first')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>

                    </div>


                    <div class="row">   
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Image Second</label>
                    <input type="file" name="support_image_second" value="{{ old('support_image_second', isset($dataresult) && isset($dataresult->support_image_second) ? $dataresult->support_image_second : '') }}" class="form-control" />
                    </div>
                    @error('support_image_second')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->support_image_second))
                    <img src="{{url($dataresult->support_image_second)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    </div>
                    <div class="row">
                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Content Second</label>
                        <textarea name="support_content_second" class="form-control">{{ old('support_content_second', isset($dataresult) && isset($dataresult->support_content_second) ? $dataresult->support_content_second : '') }}</textarea>
                    </div>
                    @error('support_content_second')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Image Third</label>
                    <input type="file" name="support_image_third" value="{{ old('support_image_third', isset($dataresult) && isset($dataresult->support_image_third) ? $dataresult->support_image_third : '') }}" class="form-control" />
                    </div>
                    @error('support_image_third')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->support_image_third))
                    <img src="{{url($dataresult->support_image_third)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    </div>
                    <div class="row">
                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Content Third</label>
                        <textarea name="support_content_third" class="form-control">{{ old('support_content_third', isset($dataresult) && isset($dataresult->support_content_third) ? $dataresult->support_content_third : '') }}</textarea>
                    </div>
                    @error('support_content_third')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Image Forth</label>
                    <input type="file" name="support_image_forth" value="{{ old('support_image_forth', isset($dataresult) && isset($dataresult->support_image_forth) ? $dataresult->support_image_forth : '') }}" class="form-control" />
                    </div>
                    @error('support_image_forth')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->support_image_forth))
                    <img src="{{url($dataresult->support_image_forth)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                </div>
                    <div class="row">

                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Support Content Forth</label>
                        <textarea name="support_content_forth" class="form-control">{{ old('support_content_forth', isset($dataresult) && isset($dataresult->support_content_forth) ? $dataresult->support_content_forth : '') }}</textarea>
                    </div>
                    @error('support_content_forth')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                  
                   
                  

                    <div class="row">

                        <div class="co-12 ">

                             <div class="row">
                                 <div class="col-9">
                                    <h3 class="text-center my-3">Section 5</h3>
                                 </div>
                                 <div class="col-3">
                                    <a class="btn btn-success" onclick="addElement()"> + Add </a>
                                 </div>
                             </div>
                            
                           
                            <div class="row px-3">
                        <div class="col-md-6 alg-self-center " >
                            <div class="mb-3">
                                <label>Section 4 Title</label>
                                <input type="text" name="section_4_title" value="{{ old('section_4_title', isset($dataresult) && isset($dataresult->section_4_title) ? $dataresult->section_4_title : '') }}" class="form-control" placeholder="Title" required />
                                </div>
                                @error('section_4_title')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                        </div>

                        <div class="col-md-6 alg-self-center" >
                            <div class="mb-3">
                                <label>Section 4 Description</label>
                                <textarea type="text" name="section_4_description"  class="form-control" placeholder="Description" required>{{ old('section_4_description', isset($dataresult) && isset($dataresult->section_4_description) ? $dataresult->section_4_description : '') }}</textarea>
                                </div>
                                @error('section_4_description')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                               
                        </div>
                    </div>

                        <div class="col-12 pb-4">

                            <div class="border rounded pb-3" id="append">
                              
                                @foreach ($dataresult->section_4_slider as $key=>$value)

                                <div class="row pt-2" @if ($key==0)
                                id='elem1'
                                @endif >
                                        
                                    <div class="col-4 alg-self-center">
                                        <img src="{{$value->image}}" style="max-width:10%;" alt="" >
                                        <label >Slider Image</label>
                                         <input type="file" class="form-control" name="section4_slider_image[]"
                                          >                                         
                                    </div>
                                    <div class="col-3 alg-self-center" >
                                        <label > Slider Heading</label>
                                         <input type="text" class="form-control" name="section4_slider_heading[]" placeholder="Heading" required value="{{$value->heading}}" >
                                        </div>
                                    <div class="col-3 alg-self-center" > 
                                        <label > Slider Description</label>
                                        <textarea type="text" class="form-control" name="section4_slider_description[]" placeholder="Description" required>{{$value->description}}</textarea>
                                    </div>
                                       @if ($key !=0)
                                          <div class="col-2 alg-self-center">
                                            <a class="btn btn-danger btn-sm " onclick="removenode(this)">Remove</a>
                                            </div>                                           
                                       @endif
                                </div>
                                    
                                @endforeach
                                
                                
                                

                            </div>

                            <div class="validationclass text-danger pt-2">@if (session()->has('slider_error'))
                                {{session('slider_error')}}
                            @endif</div>
                        </div>
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>App Section Image</label>
                    <input type="file" name="app_section_image" value="{{ old('app_section_image', isset($dataresult) && isset($dataresult->app_section_image) ? $dataresult->app_section_image : '') }}" class="form-control" />
                    </div>
                    @error('app_section_image')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    @if(isset($dataresult) && !empty($dataresult->app_section_image))
                    <img src="{{url($dataresult->app_section_image)}}" width="80px" height="80px"/>
                    @endif
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>App Download Google Playstore Url</label>
                        <input type="text" name="app_download_google_playstore_url" class="form-control" value="{{ old('app_download_google_playstore_url', isset($dataresult) && isset($dataresult->app_download_google_playstore_url) ? $dataresult->app_download_google_playstore_url : '') }}">
                    </div>
                    @error('app_download_google_playstore_url')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>App Download Google Playstore Url</label>
                        <input type="text" name="app_download_apple_playstore_url" class="form-control" value="{{ old('app_download_apple_playstore_url', isset($dataresult) && isset($dataresult->app_download_apple_playstore_url) ? $dataresult->app_download_apple_playstore_url : '') }}">
                    </div>
                    @error('app_download_google_playstore_url')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    @else
                    <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                        <label>Content<span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" required>{{ old('content', isset($dataresult) && isset($dataresult->content) ? $dataresult->content : '') }} </textarea>
                        </div>
                        @error('content')
                        <div class="validationclass text-danger pt-2">{{$message}}</div>
                        @enderror
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">status</label>
                                <select name="status" class="form-control">
                                <option value="1" {{isset($datas) && $datas->status=="1"?"selected" : ''}}>Active</option>  
                                <option value="0" {{isset($datas) && $datas->status=="0"?"selected" : ''}}>De-Active</option>
                                </select>
                            </div>
                        </div>
                  
                    @endif
                    

                     <hr class="mt-5">
                     <h4>SEO</h4>
                     <div class="row">
                         <div class="col-md-12">
                             <label class="form-label">Meta Keywords</label>
                             <input type="text" name="meta_keyword" class="form-control" value="{{old('meta_keyword', $datas->meta_keyword ??'')}}">
                         </div>
                     </div>
                      <div class="row">
                         <div class="col-md-12">
                             <label class="form-label">Meta Title</label>
                             <input type="text" name="meta_title" class="form-control" value="{{old('meta_title',$datas->meta_title ??'')}}">
                         </div>
                     </div>
                      <div class="row">
                         <div class="col-md-12">
                             <label class="form-label">Meta Description</label>
                             <textarea  name="meta_description" class="form-control">{{old('meta_description',$datas->meta_description ??'')}}</textarea>
                         </div>
                     </div>
                    <a href="{{ route('static-pages.index')}}" class="btn btn-warning btn-sm mt-5">Back</a>
                    <button class="btn btn-primary btn-sm mt-5" value="submit" >Submit</button></br></br>
              
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="container">
    <div class="row justify-content">
            <div class="col-md-12 pt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <h3 class="text-center">Section 1</h3>
                    <h4><b>Spenish Title</b></h2>
                    <input type="text" name="spenish_title" value="{{ old('spenish_title', isset($datas) && isset($datas->spenish_title) ? $datas->spenish_title : '') }}" class="form-control" />
                    </div>
                    @error('spenish_title')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div> 
                    @if (isset($datas) && $datas->id==4)
                    
                        
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label><b>Spenish Top Content</b></label>
                        <textarea name="top_content" class="form-control">{{ old('top_content', isset($dataresult) && isset($dataresult->top_content) ? $dataresult->top_content : '') }}</textarea>
                    </div>
                    @error('top_content')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                   
                    <div class="row mt-5">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <h3 class="text-center">Section 2</h3>
                    <label class=""><b>Spenish Middle Content</b></label>
                        <textarea name="middle_content" class="form-control">{{ old('middle_content', isset($dataresult) && isset($dataresult->middle_content) ? $dataresult->middle_content : '') }}</textarea>
                    </div>
                    @error('middle_content')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>


                   
                    <h3 class="text-center my-4">Section 3</h3>  
                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Spenish Service Content Left</label>
                        <textarea name="service_content_left" class="form-control">{{ old('service_content_left', isset($dataresult) && isset($dataresult->service_content_left) ? $dataresult->service_content_left : '') }}</textarea>
                    </div>
                    @error('service_content_left')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>Spenish Service content Right</label>
                        <textarea name="service_content_right" class="form-control">{{ old('service_content_right', isset($dataresult) && isset($dataresult->service_content_right) ? $dataresult->service_content_right : '') }}</textarea>
                    </div>
                    @error('service_content_right')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Spenish Service Content Middle</label>
                        <textarea name="service_content_middle" class="form-control">{{ old('service_content_middle', isset($dataresult) && isset($dataresult->service_content_middle) ? $dataresult->service_content_middle : '') }}</textarea>
                    </div>
                    @error('service_content_middle')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                    <h3 class="text-center my-3">Section 4</h3>
                    <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                    <label>Spenish Support Heading</label>
                        <textarea name="support_heading" class="form-control">{{ old('support_heading', isset($dataresult) && isset($dataresult->support_heading) ? $dataresult->support_heading : '') }}</textarea>
                    </div>
                    @error('support_heading')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Spenish Support Content First</label>
                        <textarea name="support_content_first" class="form-control">{{ old('support_content_first', isset($dataresult) && isset($dataresult->support_content_first) ? $dataresult->support_content_first : '') }}</textarea>
                    </div>
                    @error('support_content_first')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>

                    </div>


                    <div class="row">
                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Spenish Support Content Second</label>
                        <textarea name="support_content_second" class="form-control">{{ old('support_content_second', isset($dataresult) && isset($dataresult->support_content_second) ? $dataresult->support_content_second : '') }}</textarea>
                    </div>
                    @error('support_content_second')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">
                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Spenish Support Content Third</label>
                        <textarea name="support_content_third" class="form-control">{{ old('support_content_third', isset($dataresult) && isset($dataresult->support_content_third) ? $dataresult->support_content_third : '') }}</textarea>
                    </div>
                    @error('support_content_third')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    <div class="row">

                     <div class="col-md-12">
                    <div class="mb-3">
                    <label>Spenish Support Content Forth</label>
                        <textarea name="support_content_forth" class="form-control">{{ old('support_content_forth', isset($dataresult) && isset($dataresult->support_content_forth) ? $dataresult->support_content_forth : '') }}</textarea>
                    </div>
                    @error('support_content_forth')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>
                  
                   
                  

                    <div class="row">

                        <div class="co-12 ">

                             <div class="row">
                                 <div class="col-9">
                                    <h3 class="text-center my-3">Section 5</h3>
                                 </div>
                                 <div class="col-3">
                                    <a class="btn btn-success" onclick="addElement1()"> + Add </a>
                                 </div>
                             </div>
                            
                           
                            <div class="row px-3">
                        <div class="col-md-6 alg-self-center " >
                            <div class="mb-3">
                                <label>Spenih Section 4 Title</label>
                                <input type="text" name="section_4_title" value="{{ old('section_4_title', isset($dataresult) && isset($dataresult->section_4_title) ? $dataresult->section_4_title : '') }}" class="form-control" placeholder="Title" />
                                </div>
                                @error('section_4_title')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                        </div>

                        <div class="col-md-6 alg-self-center" >
                            <div class="mb-3">
                                <label>Spenish Section 4 Description</label>
                                <textarea type="text" name="section_4_description"  class="form-control" placeholder="Description" required>{{ old('section_4_description', isset($dataresult) && isset($dataresult->section_4_description) ? $dataresult->section_4_description : '') }}</textarea>
                                </div>
                                @error('section_4_description')
                                <div class="validationclass text-danger pt-2">{{$message}}</div>
                                @enderror
                               
                        </div>
                    </div>

                        <div class="col-12 pb-4">

                            <div class="border rounded pb-3" id="append">

                                @foreach($dataresult->section_4_slider as $key=>$value)

                                <div class="row pt-2" @if ($key==0)
                                id='elem2'
                                @endif >
                                        
                                    {{-- <div class="col-4 alg-self-center">
                                        <img src="{{$value->image}}" style="max-width:10%;" alt="" >
                                        <label >Slider Image</label>
                                         <input type="file" class="form-control" name="section4_slider_image[]"
                                          >                                         
                                    </div> --}}
                                    <div class="col-5 alg-self-center" >
                                        <label > Slider Heading</label>
                                         <input type="text" class="form-control" name="section4_slider_heading[]" placeholder="Heading" required value="{{$value->heading}}" >
                                        </div>
                                    <div class="col-5 alg-self-center" > 
                                        <label > Slider Description</label>
                                        <textarea type="text" class="form-control" name="section4_slider_description[]" placeholder="Description" required>{{$value->description}}</textarea>
                                    </div>
                                       @if ($key !=0)
                                          <div class="col-2 alg-self-center">
                                            <a class="btn btn-danger btn-sm " onclick="removenode(this)">Remove</a>
                                            </div>                                           
                                       @endif
                                </div>
                                    
                                @endforeach
                               
                                
                                

                            </div>

                            <div class="validationclass text-danger pt-2">@if (session()->has('slider_error'))
                                {{session('slider_error')}}
                            @endif</div>
                        </div> 
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>App Download Google Playstore Url</label>
                        <input type="text" name="app_download_google_playstore_url" class="form-control" value="{{ old('app_download_google_playstore_url', isset($dataresult) && isset($dataresult->app_download_google_playstore_url) ? $dataresult->app_download_google_playstore_url : '') }}">
                    </div>
                    @error('app_download_google_playstore_url')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    

                   
                    <div class="col-md-6">
                    <div class="mb-3">
                    <label>App Download Google Playstore Url</label>
                        <input type="text" name="app_download_apple_playstore_url" class="form-control" value="{{ old('app_download_apple_playstore_url', isset($dataresult) && isset($dataresult->app_download_apple_playstore_url) ? $dataresult->app_download_apple_playstore_url : '') }}">
                    </div>
                    @error('app_download_google_playstore_url')
                    <div class="validationclass text-danger pt-2">{{$message}}</div>
                    @enderror
                    </div>
                    </div>

                    @else
                    <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                        <label>Content</label>
                            <textarea name="spenish_content" class="form-control">{{ old('spenish_content', isset($dataresult) && isset($dataresult->spenish_content) ? $dataresult->spenish_content : '') }}</textarea>
                        </div>
                        @error('spenish_content')
                        <div class="validationclass text-danger pt-2">{{$message}}</div>
                        @enderror
                        </div>
                    </div> 
                    @endif
                    

                     <hr class="mt-5">
                     <h4>SEO</h4>
                     <div class="row">
                         <div class="col-md-12">
                             <label class="form-label">Spenish Meta Keywords</label>
                             <input type="text" name="spenish_meta_keyword" class="form-control" value="{{old('spenish_meta_keyword', $datas->spenish_meta_keyword ??'')}}">
                         </div>
                     </div>
                      <div class="row">
                         <div class="col-md-12">
                             <label class="form-label">Spenish Meta Title</label>
                             <input type="text" name="spenish_meta_title" class="form-control" value="{{old('spenish_meta_title',$datas->spenish_meta_title ??'')}}">
                         </div>
                     </div>
                      <div class="row">
                         <div class="col-md-12">
                             <label class="form-label">Spenish Meta Description</label>
                             <textarea  name="spenish_meta_description" class="form-control">{{old('spenish_meta_description',$datas->spenish_meta_description ??'')}}</textarea>
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
</form>


</div>
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
        CKEDITOR.replace('content');
        CKEDITOR.replace( 'top_content' );
        CKEDITOR.replace( 'middle_content' );
        CKEDITOR.replace( 'service_content_left' );
        CKEDITOR.replace( 'service_content_right' );
        CKEDITOR.replace( 'service_content_middle' );
        CKEDITOR.replace( 'service_content_middle' );
        CKEDITOR.replace( 'support_heading' );
        CKEDITOR.replace( 'support_content_first' );
        CKEDITOR.replace( 'support_content_second' );
        CKEDITOR.replace( 'support_content_third' );
        CKEDITOR.replace( 'support_content_forth' );
        CKEDITOR.config.allowedContent = true;
</script>

<script>

    function addElement(){
             
        var elem = document.querySelector('#elem1');
        var clone = elem.cloneNode(true);
            clone.removeAttribute('id'); 
        
            var removediv = document.createElement('div');
             removediv.classList.add('col-2','alg-self-center');
             
             var removebtn=document.createElement('a');
             removebtn.classList.add('btn','btn-danger','btn-sm','removebtn');
               
             removebtn.textContent='Remove';

             removebtn.onclick=function(){
               let parent= removebtn.parentNode.parentNode;
                parent.remove();
             }
             removediv.appendChild(removebtn);

             clone.appendChild(removediv);
              
             document.getElementById('append').appendChild(clone);
            //  elem.after(clone);
    }
    function removenode(a){

      let parent=a.parentNode.parentNode;
      parent.remove();
    }

 
  
    
</script>
<script>

    function addElement1(){
             
        var elem = document.querySelector('#elem2');
        var clone = elem.cloneNode(true);
            clone.removeAttribute('id'); 
        
            var removediv = document.createElement('div');
             removediv.classList.add('col-2','alg-self-center');
             
             var removebtn=document.createElement('a');
             removebtn.classList.add('btn','btn-danger','btn-sm','removebtn');
               
             removebtn.textContent='Remove';

             removebtn.onclick=function(){
               let parent= removebtn.parentNode.parentNode;
                parent.remove();
             }
             removediv.appendChild(removebtn);

             clone.appendChild(removediv);
              
             document.getElementById('append').appendChild(clone);
            //  elem.after(clone);
    }
    function removenode(a){

      let parent=a.parentNode.parentNode;
      parent.remove();
    }

 
  
    
</script>

@endsection