<?php

use App\Http\Controllers\Api\Pages\HomePageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\HospitalDoctorsController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\AppointmentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::POST('searching', [App\Http\Controllers\Api\V1\SearchingController::class, 'search']);

Route::POST('generate-payment-hash', [App\Http\Controllers\Api\V1\SearchingController::class, 'generatePaymentHash']);
Route::POST('testimonials', [App\Http\Controllers\Api\V1\AppointmentController::class, 'testimonials']);
Route::POST('lab-data', [App\Http\Controllers\Api\V1\SearchingController::class, 'labData']);
Route::POST('lab-tests', [App\Http\Controllers\Api\V1\SearchingController::class, 'labTests']);
Route::GET('cities-list', [App\Http\Controllers\Api\V1\SearchingController::class, 'cities']);
Route::POST('search-lab', [App\Http\Controllers\Api\V1\SearchingController::class, 'searchLab']);
Route::POST('multi-tests-lab', [App\Http\Controllers\Api\V1\SearchingController::class, 'multiTestsLab']);
Route::POST('package-list', [App\Http\Controllers\Api\V1\SearchingController::class, 'packageList']);
Route::GET('diagno-profile-list', [App\Http\Controllers\Api\V1\SearchingController::class, 'diagnoProfileList']);
Route::POST('labs-by-package', [App\Http\Controllers\Api\V1\SearchingController::class, 'labsPackage']);
Route::POST('labs-by-profile', [App\Http\Controllers\Api\V1\SearchingController::class, 'profileLabs']);
Route::POST('geo-location', [App\Http\Controllers\Api\V1\SearchingController::class, 'latLongCity']);
Route::get('/cart', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getCart'])->middleware('auth:api');
Route::post('/cart/add', [App\Http\Controllers\Api\V1\AppointmentController::class, 'addToCart'])->middleware('auth:api');

// New Start
Route::post('hospital-sign-up', [App\Http\Controllers\Api\V1\AuthController::class, 'hospitalSignUp']);
// Route::post('geo-location', [App\Http\Controllers\Api\V1\AuthController::class, 'geoLocation']);
Route::post('patient-register', [App\Http\Controllers\Api\V1\AuthController::class, 'patientSignUp']);
// Route::put('user-deactivate/{id}', [App\Http\Controllers\Api\V1\AuthController::class, 'deactivate']);
Route::middleware('auth:api')->post('/deactivate', [App\Http\Controllers\Api\V1\AuthController::class, 'deactivate']);

Route::post('hospital-doctor-sign-up', [App\Http\Controllers\Api\V1\AuthController::class, 'hospitalDoctorSignUp']);
Route::get('member', [App\Http\Controllers\Api\V1\AppointmentController::class, 'member']);
Route::POST('add-member', [App\Http\Controllers\Api\V1\AppointmentController::class, 'addmember']);
Route::POST('member-delete', [App\Http\Controllers\Api\V1\AppointmentController::class, 'memberDelete']);
Route::post('create-appointment-report', [App\Http\Controllers\Api\V1\AppointmentController::class, 'createAppointmentReport']);
// get patient appointment
Route::get('patient-appointment', [App\Http\Controllers\Api\V1\AppointmentController::class, 'patientAppointment']);
Route::get('patient-appointment', [App\Http\Controllers\Api\V1\AppointmentController::class, 'patientAppointment']);
// get hospital appointment
Route::get('hospital-appointment', [App\Http\Controllers\Api\V1\AppointmentController::class, 'hospitalAppointment']);
// hospital appointment update
Route::post('hospital-appointment-update', [App\Http\Controllers\Api\V1\AppointmentController::class, 'appointmentUpdate']);
// add appointment bill
Route::post('add-appointment-bill', [App\Http\Controllers\Api\V1\AppointmentController::class, 'appointmentBill']);
// add appointment report
Route::post('add-appointment-report', [App\Http\Controllers\Api\V1\AppointmentController::class, 'appointmentReport']);
Route::get('slider', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getslider']);
// get appointment bill
Route::get('appointment-bill-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'appointmentBillList']);
// get appointment report api
Route::get('appointment-report-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'appointmentReportList']);

//hospital doctors
Route::post('hospital-doctor', [App\Http\Controllers\Api\V1\HospitalDoctorsController::class, 'hospitalDoctors']);
Route::POST('update-city', [App\Http\Controllers\Api\Auth\AuthController::class, 'updateCity']);

// blog list
Route::get('blog-list', [App\Http\Controllers\Api\V1\ApiController::class, 'blogPosts']);

Route::middleware('auth:api')->group(function () {
    Route::POST('upload-pre', [App\Http\Controllers\Api\V1\SearchingController::class, 'uploadPre']);
    Route::GET('get-pre', [App\Http\Controllers\Api\V1\SearchingController::class, 'userPrescription']);
    Route::post('create-appointment', [App\Http\Controllers\Api\V1\AppointmentController::class, 'createAppointment']);
    Route::post('hospital-doctor', [HospitalDoctorsController::class, 'hospitalDoctors']);
    Route::delete('hospital-doctor/{id}', [HospitalDoctorsController::class, 'deleteHospitalDoctor']);
        // get hospital doctor list
    Route::get('hospital-doctor-list', [HospitalDoctorsController::class, 'hospitalDoctoeList']);
        // patient profile update
    Route::post('patient-profile-update', [App\Http\Controllers\Api\V1\AppointmentController::class, 'patientProfileUpdate']);
    Route::post('create-updateuser-address', [App\Http\Controllers\Api\V1\AppointmentController::class, 'createUpdateUserAddress']);
    Route::GET('my-addresss', [App\Http\Controllers\Api\V1\AppointmentController::class, 'myAddress']);
    Route::GET('delete-address/{id}', [App\Http\Controllers\Api\V1\AppointmentController::class, 'deleteAddress']);
    // hospital profile update
    Route::post('hospital-profile-update', [App\Http\Controllers\Api\V1\AppointmentController::class, 'hospitalProfileUpdate']);
    //get hospital profile update
    Route::get('get-hospital-profile-update', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getHospitalProfileUpdate']);
    // get patient profile update
    Route::get('get-patient-profile-update', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getPatientProfileUpdate']);
    // add review api
    Route::post('add-review', [App\Http\Controllers\Api\V1\AppointmentController::class, 'addReview']);
    // get review list
    Route::get('review-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getReviewList']);

    // get patient bill api
    Route::get('patient-bill-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'patientBill']);
    // get patient report api
    Route::get('patient-report-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'patientReportList']);

    // get submit contact
    Route::post('submit-contact', [App\Http\Controllers\Api\V1\AppointmentController::class, 'submitContact']);
    Route::get('my-profile', [App\Http\Controllers\Api\Auth\AuthController::class, 'myprofile']);
    Route::POST('update-profile', [App\Http\Controllers\Api\Auth\AuthController::class, 'updateProfile']);

     // add booking
    Route::POST('add-booking', [App\Http\Controllers\Api\V1\AppointmentController::class, 'addBooking']);
    Route::POST('complete-booking', [App\Http\Controllers\Api\V1\AppointmentController::class, 'completeBooking']);
    Route::get('get-booking', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getBooking']);

});

// get hospital category list api
Route::get('hospital-category-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'hospitalCategoryList']);

// get  faq list
Route::get('faq-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getFaqList']);
// get city list
Route::get('city-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getCityList']);
// get state list
Route::get('state-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getStateList']);

// get site setting
Route::get('site-setting-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'getSiteSettingList']);

// get category hospital api
Route::POST('category-hospital-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'categoryhospitalList']);

Route::get('hospital-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'hospitalList']);
Route::get('test-list', [App\Http\Controllers\Api\V1\AppointmentController::class, 'testList']);
Route::get('/offer', [OffersController::class, 'getOffers']);

Route::post('/apply-offer', [App\Http\Controllers\Api\V1\AppointmentController::class, 'applyOffer']);

Route::controller(AuthController::class)->group(function () {
    Route::post('sign-up', 'SignUp');
    Route::post('send-login-otp', 'sendLoginOtp');
    Route::post('login', 'login');
    Route::post('get-user', 'user');
});

Route::get('site-setting', [HomePageController::class, 'siteSetting']);
