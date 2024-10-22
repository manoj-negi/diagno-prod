<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Helper\ResponseBuilder;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Education;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    //education list api
    public function educationList()
    {
        try {
            $data = Education::select('id', 'name')->get();

            return  ResponseBuilder::success($data,  $this->success);

        } catch (\Exception $e) {
            return ResponseBuilder::error("Something went wrong", $this->serverError);
        }
    }
    // doctor list api 
    public function doctorList(Request $request)
    {
        try {
        

            $data = User::whereHas('roles', function($q)
            {
                $q->where('id','=', 3);
            });
           
            if($request->keyword){
               $data->where('name',$request->keyword)->orWhere('email',$request->keyword);
            }

            return  $data->get();
            return  ResponseBuilder::success($data,  $this->success);
        } catch (\Exception $e) {
            return ResponseBuilder::error("Something went wrong", $this->serverError);
        }
    }
    public function doctorDetails($id)
    {
        try {
            $data = User::find($id);

            return  ResponseBuilder::success($data,  $this->success);
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->serverError);
        }
    }
    public function notification($id)
    {
        try {
            $data = User::find($id);

            return  ResponseBuilder::success($data,  $this->success);
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->serverError);
        }
    }
    
}
