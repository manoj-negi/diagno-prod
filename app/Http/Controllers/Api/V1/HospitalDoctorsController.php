<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Auth\UserResource;
use App\Mail\LoginMail;
use App\Models\HospitalDoctor;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\HospitalDoctorCollection;
use App\Http\Resources\HospitalDoctorResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HospitalDoctorsController extends Controller
{

	
    public function hospitalDoctors(Request $request)
    {
        try {
   
            $validator = Validator::make($request->all(), [
                'doctor_name'     => 'required',
                'doctor_age'      => 'required',
                'doctor_category' => 'required',
            ]);
    
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
    
        
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            } else {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
    
          
            $data = [
                'hospital_id'     => $user->id,
                'doctor_name'     => $request->doctor_name,
                'doctor_age'      => $request->doctor_age,
                'doctor_category' => $request->doctor_category,
            ];
    
           
            $hospitalDoctor = HospitalDoctor::updateOrCreate(['id' => $request->id], $data);
    
        
            $response = [
                'message' => 'Hospital Doctor added',
                'data'    => $hospitalDoctor,
            ];
    
            return ResponseBuilder::success($response, $this->success);
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
    public function deleteHospitalDoctor($id)
{
    try {
        $hospitalDoctor = HospitalDoctor::find($id);
        if (!$hospitalDoctor) {
            return ResponseBuilder::error("Hospital Doctor not found", $this->notFound);
        }
        $hospitalDoctor->delete();

        $response = [
            'message' => 'Hospital Doctor deleted successfully',
        ];

        return ResponseBuilder::success($response, $this->success);
    } catch (\Exception $e) {
        return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    }
}


// hospital doctor get api 

public function hospitalDoctoeList(Request $request)
{
    try {
            

        $validator = Validator::make($request->all(), [
            'hospital_id'=>'required',
            
         
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }

        if(Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } else {
            return ResponseBuilder::error("User not found", $this->unauthorized);
        }
        // return $user;
      
        $doctor = HospitalDoctor::where('hospital_id',$request->hospital_id)->get();
       
        $this->response = new HospitalDoctorCollection($doctor);
        

   
    return ResponseBuilder::success(  $this->response, 'Hospital doctor retrieved successfully');
    } catch (\Exception $e) {
        return ResponseBuilder::error($e->getMessage(), 500);
    }
}
}
    
