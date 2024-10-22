<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Auth\UserResource;
use App\Mail\LoginMail;
use App\Helper\ResponseBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
class MyProfileController extends Controller
{

	public function myprofile()
    {
        $data = new UserResource(Auth::user());
        return ResponseBuilder::success($data,'Profile');
    }



}