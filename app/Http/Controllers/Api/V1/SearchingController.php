<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\LabProfile;
use App\Models\LabTestName;
use App\Models\LabsProfilePackage;
use App\Models\Package;
use App\Models\User;
use App\Models\LabCities;
use App\Models\LabPincode;
use App\Models\LabTest;

use App\Models\LabSelectedPackages;
use App\Models\Role;
use App\Models\UserPrescription;
use App\Models\City;
use App\Helper\ResponseBuilder;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\HospitalCollection;
use App\Http\Resources\Lab\PackageCollection;
use App\Http\Resources\Lab\TestCollection;
use App\Http\Resources\Lab\PackagePreviewCollection;
use App\Http\Resources\Lab\ProfilePreviewCollection;
use App\Http\Resources\Lab\ProfileCollection;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\Lab\TestResource;
use App\Http\Resources\HospitalColletion;
use DB;
use Auth;
class SearchingController extends Controller
{
    const MERCHANT_SALT = "lB7ehBSkap"; // Add your Salt here.
    const MERCHANT_SECRET_KEY = "QPcf1rGY"; // Add Merchant Secret Key - Optional

    public function generatePaymentHash(Request $request)
	{
        try {
            // $validator = Validator::make($request->all(), [
            //     'your_hash_name' => 'required',
            //     'your_hash_string' => 'required',
            //     'salt' => 'required',
            // ]);
            // if ($validator->fails()) {
            //     return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            // }
            
            // $response = [
            //     'hashName' => $request->your_hash_name,
            //     'hashString' => $request->your_hash_string,
            //     'hashType' => 'hashVersionV2', // or 'mcpLookup' or other hash types
            //     'postSalt' => $request->salt,
            // ];
            // $finalHash = $this->generateHash($response);
            // return($finalHash);

            $status='pending';
            $firstname='tapan';
            $amount='100';
            $txnid='text100';
            $posted_hash=null;
            $productinfo=null;
            $email='tapang786@gmail.com';
            $udf1 = null;
            $udf2 = null;
            $udf3 = null;
            $udf4 = null;
            $udf5 = null;

            // Salt should be same Post Request
            // if(isset($_POST["additionalCharges"])){
            //     $additionalCharges=$_POST["additionalCharges"];
            //     $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
            // }else{
            //     $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
            // }

            // hash('sha512',  'QPcf1rGY|' . $txnid . '|' . $amount . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|' . $params['udf1'] . '|' . $params['udf2'] . '|' . $params['udf3'] . '|' . $params['udf4'] . '|' . $params['udf5'] . '||||||' . $this->salt);
            
            $key = 'QPcf1rGY';
            $salt = 'lB7ehBSkap';
      
            $payhash_str = $key . '|' . $this->checkNull($txnid) . '|' .$this->checkNull($amount)  . '|' .$this->checkNull($productinfo)  . '|' . $this->checkNull($firstname) . '|' . $this->checkNull($email) . '|' . $this->checkNull($udf1) . '|' . $this->checkNull($udf2) . '|' . $this->checkNull($udf3) . '|' . $this->checkNull($udf4) . '|' . $this->checkNull($udf5) . '||||||' . $salt;
            $paymentHash = strtolower(hash('sha512', $payhash_str));
            return $paymentHash; 
            // return ResponseBuilder::success($this->response, 'Filter applied successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function checkNull($value) {
        if ($value == null) {
              return '';
        } else {
              return $value;
        }
    }


    // function getHashes($txnid, $amount, $productinfo, $firstname, $email, $user_credentials, $udf1, $udf2, $udf3, $udf4, $udf5,$offerKey,$cardBin)
    // {
    //       // $firstname, $email can be "", i.e empty string if needed. Same should be sent to PayU server (in request params) also.
    //       $key = 'XXXXXX';
    //       $salt = 'YYYYY';
    
    //       $payhash_str = $key . '|' . checkNull($txnid) . '|' .checkNull($amount)  . '|' .checkNull($productinfo)  . '|' . checkNull($firstname) . '|' . checkNull($email) . '|' . checkNull($udf1) . '|' . checkNull($udf2) . '|' . checkNull($udf3) . '|' . checkNull($udf4) . '|' . checkNull($udf5) . '||||||' . $salt;
    //       $paymentHash = strtolower(hash('sha512', $payhash_str));
    //       $arr['payment_hash'] = $paymentHash;
    
    //       $cmnNameMerchantCodes = 'get_merchant_ibibo_codes';
    //       $merchantCodesHash_str = $key . '|' . $cmnNameMerchantCodes . '|default|' . $salt ;
    //       $merchantCodesHash = strtolower(hash('sha512', $merchantCodesHash_str));
    //       $arr['get_merchant_ibibo_codes_hash'] = $merchantCodesHash;
    
    //       $cmnMobileSdk = 'vas_for_mobile_sdk';
    //       $mobileSdk_str = $key . '|' . $cmnMobileSdk . '|default|' . $salt;
    //       $mobileSdk = strtolower(hash('sha512', $mobileSdk_str));
    //       $arr['vas_for_mobile_sdk_hash'] = $mobileSdk;
    
    //     // added code for EMI hash
    //       $cmnEmiAmountAccordingToInterest= 'getEmiAmountAccordingToInterest';
    //       $emi_str = $key . '|' . $cmnEmiAmountAccordingToInterest . '|'.checkNull($amount).'|' . $salt;
    //       $mobileEmiString = strtolower(hash('sha512', $emi_str));
    //      $arr['emi_hash'] = $mobileEmiString;
    
    
    //       $cmnPaymentRelatedDetailsForMobileSdk1 = 'payment_related_details_for_mobile_sdk';
    //       $detailsForMobileSdk_str1 = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk1 . '|default|' . $salt ;
    //       $detailsForMobileSdk1 = strtolower(hash('sha512', $detailsForMobileSdk_str1));
    //       $arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk1;
    
    //       //used for verifying payment(optional)
    //       $cmnVerifyPayment = 'verify_payment';
    //       $verifyPayment_str = $key . '|' . $cmnVerifyPayment . '|'.$txnid .'|' . $salt;
    //       $verifyPayment = strtolower(hash('sha512', $verifyPayment_str));
    //       $arr['verify_payment_hash'] = $verifyPayment;
    
    //       if($user_credentials != NULL && $user_credentials != '')
    //       {
    //             $cmnNameDeleteCard = 'delete_user_card';
    //             $deleteHash_str = $key  . '|' . $cmnNameDeleteCard . '|' . $user_credentials . '|' . $salt ;
    //             $deleteHash = strtolower(hash('sha512', $deleteHash_str));
    //             $arr['delete_user_card_hash'] = $deleteHash;
    
    //             $cmnNameGetUserCard = 'get_user_cards';
    //             $getUserCardHash_str = $key  . '|' . $cmnNameGetUserCard . '|' . $user_credentials . '|' . $salt ;
    //             $getUserCardHash = strtolower(hash('sha512', $getUserCardHash_str));
    //             $arr['get_user_cards_hash'] = $getUserCardHash;
    
    //             $cmnNameEditUserCard = 'edit_user_card';
    //             $editUserCardHash_str = $key  . '|' . $cmnNameEditUserCard . '|' . $user_credentials . '|' . $salt ;
    //             $editUserCardHash = strtolower(hash('sha512', $editUserCardHash_str));
    //             $arr['edit_user_card_hash'] = $editUserCardHash;
    
    //             $cmnNameSaveUserCard = 'save_user_card';
    //             $saveUserCardHash_str = $key  . '|' . $cmnNameSaveUserCard . '|' . $user_credentials . '|' . $salt ;
    //             $saveUserCardHash = strtolower(hash('sha512', $saveUserCardHash_str));
    //             $arr['save_user_card_hash'] = $saveUserCardHash;
    
    //             $cmnPaymentRelatedDetailsForMobileSdk = 'payment_related_details_for_mobile_sdk';
    //             $detailsForMobileSdk_str = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk . '|' . $user_credentials . '|' . $salt ;
    //             $detailsForMobileSdk = strtolower(hash('sha512', $detailsForMobileSdk_str));
    //             $arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk;
    //       }
    
    
    //       // if($udf3!=NULL && !empty($udf3)){
    //             $cmnSend_Sms='send_sms';
    //             $sendsms_str=$key . '|' . $cmnSend_Sms . '|' . $udf3 . '|' . $salt;
    //             $send_sms = strtolower(hash('sha512',$sendsms_str));
    //             $arr['send_sms_hash']=$send_sms;
    //       // }
    
    
    //       if ($offerKey!=NULL && !empty($offerKey)) {
    //                   $cmnCheckOfferStatus = 'check_offer_status';
    //                         $checkOfferStatus_str = $key  . '|' . $cmnCheckOfferStatus . '|' . $offerKey . '|' . $salt ;
    //                   $checkOfferStatus = strtolower(hash('sha512', $checkOfferStatus_str));
    //                   $arr['check_offer_status_hash']=$checkOfferStatus;
    //             }
    
    
    //             if ($cardBin!=NULL && !empty($cardBin)) {
    //                   $cmnCheckIsDomestic = 'check_isDomestic';
    //                         $checkIsDomestic_str = $key  . '|' . $cmnCheckIsDomestic . '|' . $cardBin . '|' . $salt ;
    //                   $checkIsDomestic = strtolower(hash('sha512', $checkIsDomestic_str));
    //                   $arr['check_isDomestic_hash']=$checkIsDomestic;
    //             }
    
    
    
    //     return $arr;
    // }
    

    public function getSHA512Hash($hashData) {
        $hash = hash('sha512', $hashData);
        return $hash;
    }
    public function latLongCity(Request $request) {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
        }
      
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $apiKey = env('GOOGLE_GEOCODE_API_KEY');

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&key=' . $apiKey;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $output = json_decode($response);
        if ($output && $output->status === 'OK') {
            $results = $output->results;
            $addressComponents = $results[0]->address_components;
            $cityName = "";

            foreach ($addressComponents as $component) {
                $types = $component->types;
                if (in_array("locality", $types) && in_array("political", $types)) {
                    $cityName = $component->long_name;
                }
            }
            if ($cityName == "") {
                return ResponseBuilder::error('Error occurred while fetching the address.', $this->badRequest); 
            } 
            $City = City::where('city',$cityName)->pluck('id')->toArray();
            $LabCities = LabCities::whereIn('city',$City)->count();
            $data['available_serives'] = $LabCities > 0 ? true : false;
            $data['city_id'] = $LabCities > 0 ? ($City[0] ?? '') : '' ;
            return ResponseBuilder::success($data,'sucess');

        } 
        return ResponseBuilder::error('Error occurred while fetching the address.', $this->badRequest);  

    }
    public function getHmacSHA256Hash($hashData, $salt) {
        // $key = utf8_encode($salt);
        $key = mb_convert_encoding($salt, 'UTF-8', 'ISO-8859-1');
        $hash = hash_hmac('sha256', $hashData, $key, true);
        $hmacBase64 = base64_encode($hash);
        return $hmacBase64;
    }
    public function uploadPre(Request $request) {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
      
        } else {
            return ResponseBuilder::error("User not found", $this->unauthorized);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
        }
        $path = 'uploads/pre';
        // $file = $this->uploadDocuments($request->file('file'), $path) ?? '';
        $files = $request->file('file');
        $imageName = rand(1000,9999).$files->getClientOriginalName();
        $files->move($path, $imageName);
        $file = url($path.'/'.$imageName);
        UserPrescription::create([
            'user_id' => $user->id,
            'prescription_file' => $imageName,
            'prescription_title' => $request->prescription_title ?? '',
        ]);
        return ResponseBuilder::success('', 'success');   
    }
    public function userPrescription(Request $request)
    {
        $UserAddresses = UserPrescription::where('user_id',Auth::user()->id)->orderBy('id','desc')->get()->map(function($data){
            return [
                'id' => $data->id,
                'prescription_file' => !empty($data->prescription_file) ? url('uploads/pre',$data->prescription_file) : '',
                'prescription_title' => $data->prescription_title,
                'uploaded_date' => date('d F Y h:i A',strtotime($data->created_at)),
            ];
        });
    return ResponseBuilder::successMessage('Success',  $this->success , $UserAddresses);
    }
    public function getHmacSHA1Hash($hashData, $salt) {
        // $key = utf8_encode($salt);
        $key = mb_convert_encoding($salt, 'UTF-8', 'ISO-8859-1');
        $hash = hash_hmac('sha1', $hashData, $key);
        return $hash;
    }
    public  function generateHash($response) {
        
        $hashName = $response['hashName'];
        $hashStringWithoutSalt = $response['hashString'];
        $hashType = $response['hashType'];
        $postSalt = $response['postSalt'];
        $hash = "";

        if ($hashType === 'hashVersionV2') {
            $hash = self::getHmacSHA256Hash($hashStringWithoutSalt, self::MERCHANT_SALT);
        } elseif ($hashName === 'mcpLookup') {
            $hash = self::getHmacSHA1Hash($hashStringWithoutSalt, self::MERCHANT_SECRET_KEY);
        } else {
            $hashDataWithSalt = $hashStringWithoutSalt . self::MERCHANT_SALT;
            if ($postSalt !== null) {
                $hashDataWithSalt .= $postSalt;
            }
            $hash = self::getSHA512Hash($hashDataWithSalt);
        }

        return [$hashName => $hash];
    }

	public function search(Request $request)
	{
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }
            
            $keyword = $request->keyword;
            
            // $LabProfile = LabProfile::where('title', 'LIKE', "%$keyword%")->pluck('id')->toArray();
            // $LabProfile = PackageProfile::whereIn('profile_id',$LabProfile)->pluck('package_id')->toArray();


            $Package = Package::where('package_name', 'LIKE', "%$keyword%")->pluck('id')->toArray();

            // $LabTest = LabTest::where('test_name', 'LIKE', "%$keyword%")->pluck('id')->toArray();
            // $LabProfileTests = LabProfileTests::whereIn('test_id',$LabTest)->pluck('profile_id')->toArray();
            // $LabTest = PackageProfile::whereIn('profile_id',$LabProfileTests)->pluck('package_id')->toArray();

            // $mergedArray = [];
            // if (!empty($LabProfile) || !empty($Package) || !empty($LabTest)) {
                // $arraysToMerge = array_filter([$LabProfile, $Package, $LabTest], function ($array) {
                    // return !empty($array);
                // });
                // $mergedArray = Arr::collapse($arraysToMerge);
            // }
            
            $mergedArray = array_values(array_unique($Package));
            $getLabByCities = Helper::getLabByCities($request->city_id ?? null);
            $LabSelectedPackages = LabSelectedPackages::whereIn('package_id',$mergedArray)->whereIn('lab_id',$getLabByCities)->where('is_selected',true)->get();
            $this->response = new PackageCollection($LabSelectedPackages);
            return ResponseBuilder::success($this->response, 'Filter applied successfully!');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function labData(Request $request)
	{
        try {
            $validator = Validator::make($request->all(), [
                'lab_id' => 'required',
            ]);
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }
            $keyword = $request->search;
            $lab = User::where('id',$request->lab_id)->first();

            if(!$lab){
                return ResponseBuilder::error('Lab not found!', $this->badRequest);  
            }
            
            // $Package = Package::where('lab_id', $request->lab_id);
            // $LabProfile = LabProfile::where('lab_id', $request->lab_id);
            // $LabTest = LabTest::where('hospital_id', $request->lab_id);

            // if(!empty($request->search)){
                // $Package->where('package_name', 'LIKE', "%$keyword%");
                // $LabProfile->where('title', 'LIKE', "%$keyword%");
                // $LabTest->where('test_name', 'LIKE', "%$keyword%");
            // }
            $LabSelectedPackages = LabSelectedPackages::where('lab_id',$lab->id)->where('is_selected',true)->paginate(50);
            $this->response->lab_data = new HospitalResource($lab);
            $this->response->package = new PackageCollection($LabSelectedPackages);

            // $returnData['PackageCollection'] = new PackageCollection($Package->get());
            // $returnData['ProfileCollection'] = new ProfileCollection($LabProfile->get());
            // $returnData['TestCollection'] = new TestCollection($LabTest->get());

            return ResponseBuilder::success($this->response, 'Lab data fetch successfully');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
  public function labTests(Request $request)
{
    try {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'lab_id' => 'required',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
        }

        // Get the lab details
        $lab = User::where('id', $request->lab_id)->first();
        if (!$lab) {
            return ResponseBuilder::error('Lab not found!', $this->badRequest);  
        }

        // Get keyword if provided
        $keyword = $request->test_name;

        // Fetch tests from LabTestName table, join with labs_tests for test_name
        $testsQuery = LabTestName::query()
            ->where('lab_id', $request->lab_id);

        // Apply keyword search for test_name if provided
        if (!empty($keyword)) {
            // Using whereHas to filter on the related labs_tests table
            $testsQuery->whereHas('test', function($query) use ($keyword) {
                $query->where('test_name', 'LIKE', "$keyword%");
            });
        }

        // Paginate the results
        $tests = $testsQuery->paginate(50);

        // Map the result and structure the response
        $testDetails = $tests->map(function ($test) {
            // Fetch the test details from the labs_tests table based on test_id
            $testInfo = DB::table('labs_tests')->where('id', $test->test_id)->first();

            return [
                'id' => $test->test_id,
                'test_name' => $testInfo ? $testInfo->test_name : null,
                // Fetch amount from the LabTestName table
                'amount' => (double) $test->amount,  // Casting amount to double
                'lab_id' => $test->lab_id,
                // Safely check if the description field exists, else set as an empty string
                'description' => $test->description ?? '',
            ];
        });

        // Return paginated results along with meta information
        return ResponseBuilder::successWithPagination($tests, $testDetails, 'Lab data fetched successfully'); 
    } catch (Exception $e) {
        // Catch and return any exception
        return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    }
}

    

    // public function labTests(Request $request)
	// {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'lab_id' => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
    //         }
    //         $keyword = $request->keyword;
    //         $lab = User::where('id',$request->lab_id)->first();

    //         if(!$lab){
    //             return ResponseBuilder::error('Lab not found!', $this->badRequest);  
    //         }
           
    //         $tests = Package::where('lab_id', $request->lab_id)->where('type','test');
    //         if(!empty($keyword)){
    //             $tests->where('package_name', 'LIKE', "%$keyword%");
    //         }
    //         $tests = $tests->paginate(50);
    //         $this->response = new TestCollection($tests);
    //         return ResponseBuilder::successWithPagination($tests,$this->response, 'Lab data fetch successfully'); 
    //     }
    //     catch (exception $e) {
    //         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    //     }
    // }
    public function multiTestsLab(Request $request) {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'test' => 'required',
            'pincode' => 'required',
        ]);
    
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
        }
    
        // Split the selected test IDs into an array
        $testIds = explode(",", $request->test);
        // $tesq =$request->pincode;
        // dd($tesq);
        // Fetch labs that have the provided test IDs
        $labIds = DB::table('lab_test_name')
            ->whereIn('test_id', $testIds)
            ->pluck('lab_id')
            ->toArray();
    
        if (empty($labIds)) {
            return ResponseBuilder::error('No labs found for the provided test IDs.', $this->notFound);
        }
    
        // Start building the query to fetch labs
        $labsQuery = User::whereIn('id', $labIds);
    
        // Apply pincode filter if available
        if (!empty($request->pincode)) {
            $labByPincode = Helper::getLabByPincode($request->pincode);
            if (!empty($labByPincode)) {
                $labsQuery->whereIn('id', $labByPincode);
            }
        }
    
        // Fetch labs after applying filters
        $labs = $labsQuery->get();
    
        if ($labs->isEmpty()) {
            return ResponseBuilder::error('No labs found for the provided filters.', $this->notFound);
        }
        $labs = $labs->filter(function ($lab) {
            return $lab->name !== 'Diagnomitra';
        });
    
        if ($labs->isEmpty()) {
            return ResponseBuilder::error('No labs found after filtering.', $this->notFound);
        }
        // Map through the labs and retrieve test data for each lab
        $labdata = $labs->map(function($lab) use ($testIds) {
            // Fetch the test data for the current lab
            $tests = LabTestName::where('lab_id', $lab->id)
                ->whereIn('test_id', $testIds)
                ->get();
    
            // Structure test details for the current lab
            $testDetails = $tests->map(function($test) {
                $testInfo = DB::table('labs_tests')
                    ->where('id', $test->test_id)
                    ->first();
                return [
                    'id' => $test->test_id,
                    'test_name' => $testInfo ? $testInfo->test_name : null,  
                    'amount' => (double) $test->amount,  // Casting amount to double
                    'description' => $test->description,
                ];
            });
    
            // Return the lab data along with associated test details
            return [
                'lab_data' => [
                    'id' => $lab->id,
                    'name' => $lab->name,
                    'address' => $lab->address,
                    // 'email' => $lab->email,
                    // 'hospital_category' => $lab->hospital_category,
                    // 'hospital_description' => $lab->hospital_description,
                    // 'hospital_logo' => $lab->hospital_logo,
                    // 'review_count' => $lab->review_count ?? 0,
                    // 'patientCount' => $lab->patientCount ?? 0,
                    // 'avg_rating_count' => $lab->avg_rating_count ?? '0',
                ],
                'test_data' => $testDetails,
            ];
        });
    
        // Return the final response
        return ResponseBuilder::success($labdata, 'Result fetched successfully');
    }
    

    
    // public function multiTestsLab(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'test' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
    //     }
    //     $testIds = explode(",",$request->test);
    //     $labId = LabSelectedPackages::whereIn('package_id', $testIds)
    //             ->where('is_selected',true)
    //             ->groupBy('lab_id')
    //             ->havingRaw('COUNT(DISTINCT package_id) = ' . count($testIds))
    //             ->pluck('lab_id')
    //             ->toArray();

    //     $labs = User::whereIn('id', $labId);
    //     if(!empty($request->city_id)){
    //         $getLabByCities = Helper::getLabByCities($request->city_id ?? null);
    //         $labs->whereIn('id',$getLabByCities);
    //     }
    //     $labs = $labs->get();
    //     $labdata = new HospitalCollection($labs);
    //     $tests = Package::whereIn('id',$testIds)->where('type','test')->get();
    //     $testData = new TestCollection($tests);
    //     $labdata = $labdata->map(function($data) use ($labId,$testIds){
    //     $LabSelectedPackages = LabSelectedPackages::whereIn('package_id',$testIds)->where('lab_id',$data->id)->get();
    //     return [
    //         'lab_data' => $data,
    //         'test_data' => $LabSelectedPackages->map(function($testData){ 
    //             return [ 
    //                 'id' => isset($testData->packageData) ? $testData->packageData->id : '', 
    //                 'test_name' => isset($testData->packageData) ? $testData->packageData->package_name : '', 
    //                 'amount' => isset($testData->amount) ? $testData->amount : (isset($testData->packageData->amount) ? $testData->packageData->amount : '0'), 
    //                 'description' => isset($testData->packageData) ? $testData->packageData->description : '', 
    //             ]; 
    //         }),
    //     ];
    //    });
    //    return ResponseBuilder::success($labdata, 'result fetch successfully');
    // }
    // public function searchLab(Request $request)
    // {
    //     try {
    //         $keyword = $request->keyword;
    
    //         // Get labs with role 'Lab'
    //         $lab = Role::where('role', 'Lab')->first()->users();
    
    //         /** Data according to pincode */
    //         if (!empty($request->pincode_id)) {
    //             // Assuming you have a helper function to get labs by pincode
    //             $getLabByPincode = Helper::getLabByPincode($request->pincode_id ?? null);
    //             $lab->whereIn('id', $getLabByPincode);
    //         }
            
    //         // Get lab IDs
    //         $lab = $lab->pluck('id')->toArray();
    
    //         // Search keyword, modify based on your logic
    //         $keyword = Helper::searchShortKeys($keyword);
            
    //         // Fetch tests from labs_tests table based on labs and search keyword (test_name)
    //         $tests = LabTestName::whereIn('lab_id', $lab)
    //         ->join('users', 'lab_test_name.lab_id', '=', 'users.id')  // Join the users table
    //         ->where('test_id', 'LIKE', "%$keyword%")                  // Filter by test_id
    //         ->select('lab_test_name.*', 'users.name as lab_name')      // Select the lab_name from users
    //         ->get();
        
    // // dd($tests);
    //         // Return success response with fetched tests
    //         // $this->response = new TestCollection($tests);
    //         $this->response = ($tests);

    //         return ResponseBuilder::success($this->response, 'Tests fetched successfully');
            
    //     } catch (Exception $e) {
    //         // Handle exception
    //         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    //     }
    // }
    
    public function searchLab(Request $request)
    {
        try {
            $keyword = $request->keyword;
    
            // Get labs with role 'Lab'
            $lab = Role::where('role', 'Lab')->first()->users();
    
            /** Data according to pincode */
            if (!empty($request->pincode)) {
                // Join with lab_pincode and pincode tables to filter by pincode
                $lab->join('lab_pincode', 'users.id', '=', 'lab_pincode.lab_id')
                    ->join('pincode', 'lab_pincode.pincode_id', '=', 'pincode.id')
                    ->where('pincode.pincode', $request->pincode);
            }
    
            // Get lab IDs after filtering by pincode
            $lab = $lab->pluck('users.id')->toArray();
    
            // Ensure we have lab IDs to search for
            if (empty($lab)) {
                return ResponseBuilder::error("No labs found for the provided pincode", 404);
            }
    
            // Process the search keyword (optional)
            $keyword = Helper::searchShortKeys($keyword);
    
            // Fetch tests from labs_tests and lab_test_name based on lab IDs and search keyword
            $tests = LabTestName::whereIn('lab_test_name.lab_id', $lab)
                ->join('users', 'lab_test_name.lab_id', '=', 'users.id')  // Join the users table
                ->join('labs_tests', 'lab_test_name.test_id', '=', 'labs_tests.id') // Join labs_tests to get test_name
                ->where('labs_tests.test_name', 'LIKE', "%$keyword%") // Use 'test_name' from labs_tests
                ->select('lab_test_name.*', 'users.name as lab_name', 'labs_tests.test_name') // Select necessary fields
                ->get();
    
            // Check if any tests were found
            if ($tests->isEmpty()) {
                return ResponseBuilder::error("No tests found for the provided search keyword", 404);
            }
    
            // Return success response with fetched tests
            return ResponseBuilder::success($tests, 'Tests fetched successfully');
            
        } catch (Exception $e) {
            // Handle exception with appropriate error message and code
            return ResponseBuilder::error(__($e->getMessage()), 500);
        }
    }
    
    public function labsPackage(Request $request)
    {
        try {
            // Validate the request to ensure 'package_name' is provided
            $request->validate([
                'package_name' => 'required|string',
            ]);
    
            // Retrieve labs by searching for packages with the specified 'package_name'
            $labsPackages = Package::where('package_name', 'LIKE', '%' . $request->package_name . '%')
                ->with('lab')  // Ensure the lab relationship is loaded
                ->get();
    
            // Check if no labs were found for the given package_name
            if ($labsPackages->isEmpty()) {
                return ResponseBuilder::error('No labs found for the given package name.', $this->badRequest);
            }
    
            // Format the response to include relevant data for each lab and package
            $this->response = $labsPackages->map(function ($data) {
                return [
                    // "lab_id" => $data->lab ? $data->lab->id : null,  // Check if lab exists
                    // "lab_name" => $data->lab ? $data->lab->name : 'N/A',  // Check if lab exists
                    "id" => $data->id,
                    "package_name" => $data->package_name,
                    "amount" => $data->amount ?? '0.00',  // Handle null amount
                    "is_lifestyle" => $data->is_lifestyle,
                    "is_frequently_booking" => $data->is_frequently_booking,
                    "lab" => isset($data->lab) ? new HospitalResource($data->lab) : '',

                ];
            });
    
            // Return the success response with the mapped data
            return ResponseBuilder::success($this->response, 'Labs fetched successfully');
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error fetching labs: ' . $e->getMessage());
    
            // Return the error response
            return ResponseBuilder::error(__('An error occurred while fetching labs.'), $this->serverError);
        }
    }
    

    // public function profileLabs(Request $request)
	// {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'profile_id' => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
    //         }

    //         /**data according cities */

    //         $getLabByCities = Helper::getLabByCities($request->city_id ?? null);
    //         // $getLabByCities = Helper::getLabByCities();

    //         $LabSelectedPackages = LabSelectedPackages::where('package_id',$request->profile_id)->whereIn('lab_id',$getLabByCities)->where('is_selected',true)->whereHas('packageData', function ($query) {
    //             return $query->where('type', '=', 'profile');
    //         })->get();
    //         $this->response = $LabSelectedPackages->map(function($data){
    //             return [
    //                 "id"                => isset($data->packageData) ? $data->packageData->id : '',
    //                 "package_name"      => isset($data->packageData) ? $data->packageData->package_name : '',            
    //                 "amount"            => !empty($data->amount) ? $data->amount : (isset($data->packageData) ? $data->packageData->amount : '0.00'),            
    //                 "lab"               => isset($data->labDetails) && !empty($data->labDetails) ? new HospitalResource($data->labDetails) : '',            
    //                 "tests"          => isset($data->packageData->getChilds) && !empty($data->packageData->getChilds) ? new ProfileCollection($data->packageData->getChilds) : [],            
    //             ];
    //         });
    //         return ResponseBuilder::success($this->response, 'Labs fetch successfully');   
    //     }
    //     catch (exception $e) {
    //         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    //     }
    // }

    public function profileLabs(Request $request)
{
    try {
        // Validate that lab_profile_name is provided
        $validator = Validator::make($request->all(), [
            'lab_profile_name' => 'required',  // Validate the lab_profile_name
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
        }

        // Get labs filtered by pincode (if provided)
        // $getLabByPincode = Helper::getLabByPincode($request->pincode_id ?? null);

        // Fetch labs that are associated with the profile name
        $LabProfiles = LabProfile::where('profile_name', 'LIKE', '%' . $request->lab_profile_name . '%')
            // ->whereIn('lab_id', $getLabByPincode) // Filter labs by pincode if provided
            ->with('lab') // Ensure the lab relationship is loaded
            ->get();

        // Map the result to the desired format
        $this->response = $LabProfiles->map(function ($data) {
            return [
                "id" => $data->id,
                "profile_name" => $data->profile_name,
                "amount" => !empty($data->amount) ? $data->amount : '0.00',
                "lab" => isset($data->lab) ? new HospitalResource($data->lab) : '',
            ];
        });

        // Return successful response with mapped data
        return ResponseBuilder::success($this->response, 'Labs fetched successfully');
    } catch (Exception $e) {
        // Catch and return any errors
        return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    }
}

    
    

    public function packageList(Request $request)
    {
        try {
            // Query to get all packages and order by id desc
            $Package = Package::orderBy('id', 'desc');
    
            // Apply filters based on the request parameters
            if ($request->has('is_lifestyle') && $request->is_lifestyle) {
                $Package->where('is_lifestyle', true);
            }
            if ($request->has('is_frequently_booking') && $request->is_frequently_booking) {
                $Package->where('is_frequently_booking', true);
            }
    
            // Get the filtered results
            $Package = $Package->get();
    
            // Return the response as a collection
            $this->response = new PackagePreviewCollection($Package);
    
            return ResponseBuilder::success($this->response, 'Package list');   
        } catch (Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
    
    // public function packageList(Request $request)
	// {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'type' => 'required|in:all,is_lifestyle,is_frequently_booking',
    //         ]);
    //         if ($validator->fails()) {
    //             return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
    //         }

    //         $Package = Package::orderBy('id','desc')->where('type','package');
    //         if($request->type=='is_lifestyle'){
    //             $Package->where('is_lifestyle',true);
    //         }
    //         if($request->type=='is_frequently_booking'){
    //             $Package->where('is_frequently_booking',true);
    //         }
    //         $Package = $Package->get();
    //         $this->response = new PackagePreviewCollection($Package);

    //         return ResponseBuilder::success($this->response, 'Package list');   
    //     }
    //     catch (exception $e) {
    //         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    //     }
    // }

    // public function diagnoProfileList(Request $request)
	// {
    //     try {
    //         $profiles = Package::orderBy('id','desc')->where('type','profile')->where('lab_id',1);
    //         $profiles = $profiles->get();
    //         $this->response = new ProfilePreviewCollection($profiles);
    //         return ResponseBuilder::success($this->response, 'Profile list');   
    //     }
    //     catch (exception $e) {
    //         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
    //     }
    // }

    public function diagnoProfileList(Request $request)
    {
        try {
          
            // Start building the query for profiles
            $profilesQuery = LabProfile::query()
                ->join('users', 'lab_profile.lab_id', '=', 'users.id')
                ->select('lab_profile.*', 'users.name as lab_name');
    
            
            // Fetch the profiles in descending order of ID
            $profiles = $profilesQuery->orderBy('lab_profile.id', 'desc')->get();
    // dd($profiles);
            // Wrap profiles in the response collection
            $this->response =  new ProfilePreviewCollection($profiles);
    
            // Return a successful response with the profiles
            return ResponseBuilder::success($this->response, 'Profile list');
        }
        catch (Exception $e) {
            // Log the exception for debugging (optional)
            Log::error('Error fetching profile list: ' . $e->getMessage());
    
            // Return an error response
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
    public function cities(Request $request)
	{
        try {
            $data = DB::table('cities')->select('id','city')->get();

            return ResponseBuilder::success($data, 'Cities list');   
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

}
