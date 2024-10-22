<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\settings;
use App\Models\HospitalCategory;
use App\Models\User;
use App\Models\State;
use Auth;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "Setting";
        $data['page_name'] = "Setting";
        $data['updates'] = settings::pluck("value", "key");
        return view("admin.settings.create",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Setting";
        $data['page_name'] = "Setting";
        $d["updates"] = settings::pluck("value", "key");
        return view('admin.settings.create',$d,$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $validate = $request->validate([
            'country' => 'required',
            'admin_commission' => 'required',
            'name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'number' => 'required|digits:10|numeric',
            'email' => 'required|email',
            'address' => 'required',
            'pincode' => 'required|digits:6|numeric',
        
        ]);
     
        // if(isset($request->facebook) && !empty($request->facebook))
        // {
        //     $request->validate([
        //         'facebook' => 'url',    
        //     ]);
        //     $setting['facebook'] = $request->facebook;

        // }
        // else
        // {
        //     $setting['facebook'] = $request->facebook;
        // }
    

        // $setting['country'] = $request->country;
        // $setting['name'] = $request->name;
        // $setting['state'] = $request->state;
        // $setting['city'] = $request->city;
        // $setting['number'] = $request->number;
        // $setting['pincode'] = $request->pincode;
        // $setting['email'] = $request->email;
        // $setting['url'] = $request->url;
        // $setting['address'] = $request->address;
       
        // $setting['twitter'] = $request->twitter;
        // $setting['instagram'] = $request->instagram;
        // $setting['linkedin'] = $request->linkedin;
        // $setting['footerconent'] = $request->footerconent;
        // $setting['meta_keyword'] = $request->meta_keyword;
        // $setting['meta_title'] = $request->meta_title;
        // $setting['meta_description'] = $request->meta_description;


        $settings = $request->except(['_token', 'logo', 'favicon', 'footer_logo']);

        foreach ($settings as $key => $value) {
            # code...
            $data = settings::updateOrCreate(['key' => $key], [
                'value' => $value,
            ]);
        }

        if($request->file('logo')) {
            $file = $request->file('logo');
            $imageName = $this->UploadImage($file, 'Images');
            settings::updateOrCreate(['key' => 'logo'], [
                'value' => $imageName,
            ]);
        }
        if($request->file('favicon')) {
            $file = $request->file('favicon');
            $imageName = $this->UploadImage($file, 'Images/favicon');
            settings::updateOrCreate(['key' => 'favicon'], [
                'value' => $imageName,
            ]);
        }

        if($request->file('footer_logo')) {
            $file = $request->file('footer_logo');
            $imageName = $this->UploadImage($file, 'Images/footer_logo');
            settings::updateOrCreate(['key' => 'footer_logo'], [
                'value' => $imageName,
            ]);
        }
       
        
        // foreach($setting as $keys=>$value)
        // {
        //     if($keys=="logo" || $request->hasfile("logo"))
        //     {
        //         $logos = $request->file('logo');
        //          $logo_name = $logos->getClientOriginalName();
        //          $path = "images";
        //          $logos->move($path,$logo_name);
        //          settings::UpdateOrCreate([
        //               "key" => $keys,
        //          ],[
        //                "value" => $logo_name,
        //          ]);
        //     }   

        //     if($keys=="favicon" || $request->hasfile("favicon"))
        //     {
        //          $logos = $request->file('favicon');
        //          $favicon_name = $logos->getClientOriginalName();
        //          $path = "images/favicon";
        //          $logos->move($path,$favicon_name);
        //          settings::UpdateOrCreate([
        //               "key" => $keys,
        //          ],[
        //                "value" => $favicon_name,
        //          ]);
        //     }  

        //     if($keys=="footer_logo" || $request->hasfile("footer_logo"))
        //     {
                
        //         $logos = $request->file('footer_logo');
        //         $footer_logo = $logos->getClientOriginalName();
        //         $path = "images/footer_logo";
        //         try {
        //             $logos->move($path, $footer_logo);
        //         } catch (Exception $e) {
        //             return $e->getMessage();
        //             die();
        //         }
                
        //         settings::UpdateOrCreate(["key" => $keys],["value" => $footer_logo]);
        //     }  

        //     if($value)
        //     {
        //         $data = settings::UpdateOrCreate([

        //             "key" => $keys, 
        //             ],[
        //             "value" => $value,
        //         ]);
        //     }
        // }

        if($data)
            {
                if($request->id)
                {
                    return redirect()->route('site-settings.index')->with('success','Setting is successfully updated'); 
                }
                else
                {
                    return redirect()->route('site-settings.index')->with('success','Setting is successfully created'); 
                } 
            }else
            {
                return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
            }      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function UploadImage($file, $path)
    {
        // code...
        $filename = $file->getClientOriginalName();
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $imageName = time().uniqid().str_replace(' ','-',$filename).'.'.$file->extension();
        // Public Folder
        $file->move(public_path($path), $imageName);
        // $file->storeAs($path, $imageName);
        return $imageName; 
    }

    // public function view(Request $request)
    // {
    //     $updates['updates'] = settings::all();
    
    //     dd()
    //     return view('site-settings.create',$updates);
    // }


public function hospital_profile(){

    $data['title'] = "hospital profile";
    $data['page_name'] = "hospital profile";
    $result['HospitalCategory'] = HospitalCategory::all();
    $result['State'] = State::all();

    return view('admin.settings.hospital_profile',$data,$result);
    
}
public function hospital(Request $request)
    {
        
       $data= City::where('state_id', $request->state_id)->get();
    
        $outputData = '';
       
         if(isset($data) && count($data)>1){    
            foreach($data as $key => $value){
                if(!empty($value)){
                    $valueID = $value->id;
                    $valueTitle = $value->city;
                    $outputData .= "<option value='$valueID'>".$valueTitle."</option>";
                }
            }
         }
         return response()->json(['status' => true, 'outputData' => $outputData]);
    }

}
