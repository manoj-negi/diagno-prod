<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Auth\UserResource;
use App\Mail\LoginMail;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
	public function patientRegister(Request $request)
	{
        try {
            $validator = Validator::make($request->all(), [
                'name'     			 => 'required',
                'email'     		 => 'required|email',
                'number' => 'required|numeric|digits_between:10,12',
                'address' => 'required',
                 'dob' => 'required|date_format:Y-m-d'


            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }


                $user = User::create([
                	'name'      => $request->name,
                    'email'     => $request->email,
                    'number' => $request->number,
                    'address' => $request->address,
                    'dob' => $request->dob,
                   

                ]);
                $user->roles()->sync(2);
            return ResponseBuilder::success($this->response, 'Registered Successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
}

