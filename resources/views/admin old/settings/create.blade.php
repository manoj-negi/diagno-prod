@extends('layouts.adminCommon')
@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-12 mt-2">
            <div class="card mt-3">
                <div class="card-body">
                    <form action="{{route('site-settings.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <!-- <input type="hidden" name="id" value="{{$updates['id'] ?? ''}}"> -->
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Business logo</label>
                                <input type="file" name="logo" value="{{$updates['logo'] ?? ''}}"
                                    class="form-control " />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Favicon Icon</label>
                                <input type="file" name="favicon" value="{{$updates['favicon'] ?? ''}}"
                                    class="form-control " />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                @if(isset($updates) && !empty($updates['logo']))
                                <img src="{{url('Images').'/'.$updates['logo'] ?? ''}}" width="70px" height="60px"
                                    class="mt-2" />
                                @endif
                            </div>
                            <div class="col-md-6 mb-2">
                                @if(isset($updates) && !empty($updates['favicon']))
                                <img src="{{url('Images/favicon').'/'.$updates['favicon'] ?? ''}}" width="70px" height="60px"
                                    class="mt-2" />
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 ">
                                <label class="form-label">Country</label>
                                <input type="text" value="{{ old('country', isset($updates) && isset($updates['country']) ? $updates['country'] : '') }}" name="country"
                                    class="form-control">
                                @error('country')
                                <div class="alert text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Business Name</label>
                                <input type="text" value="{{ old('name', isset($updates) && isset($updates['name']) ? $updates['name'] : '')}}" name="name" class="form-control">
                                @error('name')
                                <div class="alert text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mt-2">
                                <label class="form-label">State</label>
                                <input type="text" value="{{ old('state', isset($updates) && isset($updates['state']) ? $updates['state'] : '') }}" name="state"
                                    class="form-control">
                                    @error('state')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>

                            <div class="col-md-6 ">
                                <label class="form-label">City</label>
                                <input type="text" value="{{ old('city', isset($updates) && isset($updates['city']) ? $updates['city'] : '') }}" name="city" class="form-control">
                                 @error('city')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 ">
                                <label class="form-label">Helpline Number</label>
                                <input type="text" value="{{ old('number', isset($updates) && isset($updates['number']) ? $updates['number'] : '') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                                .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" name="number"
                                    class="form-control">
                                    @error('number')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                           
                            <div class="col-md-6 ">
                                <label class="form-label">Postcode</label>
                                <input type="text" value="{{ old('pincode', isset($updates) && isset($updates['pincode']) ? $updates['pincode'] : '') }}" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight']
                                .includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" name="pincode"
                                    class="form-control">
                                    @error('pincode')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 ">
                                <label class="form-label">Email</label>
                                <input type="email" value="{{ old('email', isset($updates) && isset($updates['email']) ? $updates['email'] : '') }}" name="email"
                                    class="form-control">
                                    @error('email')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Site Url</label>
                                <input type="text" value="{{ old('url', isset($updates) && isset($updates['url']) ? $updates['url'] : '') }}" name="url" class="form-control">
                                @error('url')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control">{{ old('address', isset($updates) && isset($updates['address']) ? $updates['address'] : '') }}</textarea>
                                 @error('address')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                            </row>
                             <div class="row">
                            <div class="col-md-12">
                                 <label class="form-label">Footer Content</label>
                                <textarea name="footerconent" class="form-control">{{ old('footerconent', isset($updates) && isset($updates['footerconent']) ? $updates['footerconent'] : '') }}</textarea>
                                 @error('footerconent')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Footer Logo</label>
                                <input type="file" name="footer_logo" value="{{$updates['footer_logo'] ?? ''}}" class="form-control"/>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                @if(isset($updates) && !empty($updates['footer_logo']))
                                <img src="{{url('Images/footer_logo').'/'.$updates['footer_logo'] ?? ''}}" width="70px" height="60px"
                                    class="mt-2" />
                                @endif
                            </div>
                         </div>
                            <div class="row mt-4">
                                 <hr>
                                <div class="col-md-6">
                                    <h4>Social Media</h4>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6 ">
                                <label class="form-label">Facebook</label>
                                <input type="text" value="{{ old('facebook', isset($updates) && isset($updates['facebook']) ? $updates['facebook'] : '') }}" name="facebook"
                                    class="form-control">
                                    @error('facebook')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Instagram</label>
                                <input type="text" value="{{ old('instagram', isset($updates) && isset($updates['instagram']) ? $updates['instagram'] : '') }}" name="instagram" class="form-control">
                            </div>
                            @error('instagram')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 ">
                                <label class="form-label">Twitter</label>
                                <input type="text" value="{{ old('twitter', isset($updates) && isset($updates['twitter']) ? $updates['twitter'] : '') }}" name="twitter"
                                    class="form-control">
                                    @error('twitter')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Linkedin</label>
                                <input type="text" value="{{ old('linkedin', isset($updates) && isset($updates['linkedin']) ? $updates['linkedin'] : '') }}" name="linkedin"
                                    class="form-control">
                                    @error('linkedin')
                                    <div class="alert text-danger">{{$message}}</div>
                                     @enderror
                            </div>
                        </div>
                       
                   
                             <hr>
                           <h4>SEO</h4>
                            <div class="row">
                                <div class="col">
                             <label class="form-label">Meta Keywords</label>
                             <input type="text" name="meta_keyword" class="form-control" value="{{ old('meta_keyword', isset($updates) && isset($updates['meta_keyword']) ? $updates['meta_keyword'] : '') }}">
                              </div>
                            </div>
                           <div class="row">
                              <div class="col">
                             <label class="form-label">Meta Title</label>
                             <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', isset($updates) && isset($updates['meta_title']) ? $updates['meta_title'] : '') }}">
                               </div>
                            </div>
                        <div class="row">
                         <div class="col">
                             <label class="form-label">Meta Description</label>
                             <textarea  name="meta_description" class="form-control">{{ old('meta_description', isset($updates) && isset($updates['meta_description']) ? $updates['meta_description'] : '') }}</textarea>
                         </div>
                     </div>
                        </div>
                </div>
            </div>
        </div>
        </div>
     <div class="container">
            <div class="row justify-content">
                <div class="col-md-12 mt-2">
                    <div class="card mt-3">
                        <div class="card-body">
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Spenish Address</label>
                                        <textarea name="spenish_address" class="form-control">{{ old('spenish_address', isset($updates) && isset($updates['spenish_address']) ? $updates['spenish_address'] : '') }}</textarea>
                                         @error('address')
                                            <div class="alert text-danger">{{$message}}</div>
                                             @enderror
                                    </div>
                                    </div>
                                     <div class="row">
                                    <div class="col-md-12">
                                         <label class="form-label">Spenish Footer Content</label>
                                        <textarea name="spenish_footerconent" class="form-control">{{ old('spenish_footerconent', isset($updates) && isset($updates['spenish_footerconent']) ? $updates['spenish_footerconent'] : '') }}</textarea>
                                         @error('footerconent')
                                            <div class="alert text-danger">{{$message}}</div>
                                             @enderror
                                    </div>
                                </div>
                                   
                  
                                     <hr>
                                   <h4>SEO</h4>
                                    <div class="row">
                                        <div class="col">
                                     <label class="form-label">Spenish Meta Keywords</label>
                                     <input type="text" name="spenish_meta_keyword" class="form-control" value="{{ old('spenish_meta_keyword', isset($updates) && isset($updates['spenish_meta_keyword']) ? $updates['spenish_meta_keyword'] : '') }}">
                                      </div>
                                    </div>
                                   <div class="row">
                                      <div class="col">
                                     <label class="form-label">Spenish Meta Title</label>
                                     <input type="text" name="spenish_meta_title" class="form-control" value="{{ old('spenish_meta_title', isset($updates) && isset($updates['spenish_meta_title']) ? $updates['spenish_meta_title'] : '') }}">
                                       </div>
                                    </div>
                                <div class="row">
                                 <div class="col">
                                     <label class="form-label">Spenish Meta Description</label>
                                     <textarea  name="meta_description" class="form-control">{{ old('meta_description', isset($updates) && isset($updates['meta_description']) ? $updates['meta_description'] : '') }}</textarea>
                                 </div>
                             </div>
                                    <div class="pt-3">
                                        <button type="submit" value="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
</div>
</div>
</div>
@endsection