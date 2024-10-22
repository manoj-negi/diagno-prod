<?php

use App\Http\Controllers\TypeOfConsultationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicalTestController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentBillController;
use App\Http\Controllers\AppointmentReportController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RatingReviewController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\DoctorRecommendedController;
use App\Http\Controllers\SubscriptionInventoryController;
use App\Http\Controllers\HospitalCategoryController;
use App\Http\Controllers\PatientReportController;
use App\Http\Controllers\AppointmentReferEaringController;
use App\Http\Controllers\HospitalDoctorController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\LabRegisterController;
use App\Http\Controllers\UpComeingAppointmentController;
use App\Http\Controllers\TestRequestController;
use App\Http\Controllers\MailTemplateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\RadioLogyController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Admin\BlogPostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/invoice/{id}', [HomeController::class, 'invoice']);
Route::POST('import-csv', [HomeController::class, 'importCsv']);
Route::get('/cron_job', [HomeController::class, 'ShowUserlist'])->name('cron_job');
Route::GET('user-account-delete', [HomeController::class,'UserAccountDelete']);
Route::post('user-account-delete', [HomeController::class,'UserAccountDeletepost']);
Route::group(['middleware' => 'auth'], function () {
    //
    Route::GET('/get-city', [LabRegisterController::class,'getcity']);
    Route::GET('/get-citys', [HospitalController::class,'getcity']);
    Route::any('/change-password', 'App\Http\Controllers\HospitalController@changePassword')->name('change.password');

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('test-request', TestRequestController::class);
    Route::resource('medicaltests', MedicalTestController::class);
    Route::resource('medicines', MedicationController::class);
    Route::resource('diseases', DiseaseController::class);
    Route::resource('mails-template', MailTemplateController::class);
    Route::delete('mails-template/destroy', 'MailTemplateController@massDestroy')->name('mails-template.massDestroy');
    Route::resource('symptoms', SymptomController::class);
    Route::resource('static-pages', StaticPageController::class);
    Route::resource('users', UserController::class);
    Route::resource('mail', MailController::class);
    Route::resource('specialities', SpecialityController::class);
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('doctor', DoctorController::class);
    Route::resource('lab', HospitalController::class);
    Route::resource('patient', PatientController::class);
    Route::resource('site-settings', SettingsController::class);
    Route::get('hospital-profile', [SettingsController::class, 'hospital_profile']);
    Route::get('hospital-status-update/{id}', [HospitalController::class,'hospitalUpdate'])->name('hospital-status-update');
    Route::any('appointments-status-update/{id}', [AppointmentController::class,'appointmentUpdate'])->name('appointments-status-update');
    Route::any('lab-test-status-update/{id}', [LabTestController::class,'labtestUpdate'])->name('lab-test-status-update');
    Route::any('package-amount-update', [PackageController::class,'amountUpdate'])->name('package-amount-update');
    Route::any('packages', [PackageController::class,'packageAmountUpdate'])->name('package');

    Route::resource('review', ReviewController::class);
    Route::resource('educations', EducationController::class);
    Route::resource('subscription', SubscriptionController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('lab-category', HospitalCategoryController::class);
    Route::resource('lab-test', LabTestController::class);
    Route::GET('/diagno-tests', [LabTestController::class ,'indexDiagno']);
    Route::resource('radiology', RadioLogyController::class);
    Route::resource('wallet', WalletController::class);
    Route::GET('/diagno-radiology', [RadioLogyController::class ,'indexDiagno']);
    // Route::GET('lab-tests/{id}', 'LabTestController@updatestatuss')->name('lab-tests');
    Route::get('lab-tests/{id}', [LabTestController::class, 'updatestatuss']);
    Route::resource('sliders', SliderController::class);
    Route::resource('offer', OffersController::class);
    Route::resource('promo', PromoController::class);
    Route::resource('patient-report', PatientReportController::class);
    Route::resource('lab-register', LabRegisterController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('upcomeing-appointments', UpComeingAppointmentController::class);
    // Route::POST('patient-report-upload', [AppointmentController::class,'reportFile']);
    Route::any('upload-report', [AppointmentController::class, 'reportFile']);
    Route::resource('appointments-bills', AppointmentBillController::class);
    Route::resource('appointments-refer', AppointmentReferEaringController::class);
    Route::resource('profile', ProfileController::class);
    Route::GET('/get-test', [ProfileController::class,'test']);
    Route::GET('/get-tests-by-parent', [ProfileController::class,'getTestsByParent']);

    Route::GET('/profile-destroy/{id}', [ProfileController::class,'destoryProfile']);
    Route::GET('/diagno-profiles', [ProfileController::class,'indexDiagno']);
    Route::GET('/diagno-package', [PackageController::class,'indexDiagno']);
    // Route::delete('/deleted/{id}', [ProfileController::class, 'deleted'])->name('deleted');
    Route::GET('/deleted/{package_id}/{profile_id}', [ProfileController::class, 'deleted']);
    Route::resource('package', PackageController::class);
    Route::GET('/package-destroy/{id}', [PackageController::class,'destoryPackage']);
    Route::GET('/get-profile', [PackageController::class,'packageProfile']);
    Route::GET('/get-package-profile', [PackageController::class,'getPackageProfile']);
    Route::GET('/deleted-profile/{package_id}/{profile_id}', [PackageController::class, 'deleted']);
    Route::resource('appointments-report', AppointmentReportController::class);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::get('backforward/{id}', [AppointmentController::class, 'forward'])->name('backforward.show');
    // Route::get('update-status/{id}', AppointmentController::class, 'updatestatus');
    Route::get('update-status/{id}', [AppointmentController::class, 'updatestatus']);
    Route::resource('questions', QuestionController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('ratingreviews', RatingReviewController::class);
    Route::resource('supports', SupportController::class);
    // city
    Route::resource('city', CityController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('doctor-recommended', DoctorRecommendedController::class);
    Route::get('/subscriptioninventory', [SubscriptionInventoryController::class, 'index'])->name('subscriptioninventory');

    Route::get('get-today-appointments', [DoctorController::class, 'getAppointments'])->name('get-today-appointment');

    Route::resource('type-of-consultations', TypeOfConsultationController::class);
    Route::resource('hospital-doctors', HospitalDoctorController::class);
   

Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('blogposts', BlogPostController::class);
});
Route::post('/admin/blogposts/toggle-status', [BlogPostController::class, 'toggleStatus'])->name('admin.blogposts.toggleStatus');

});

Route::get('state', [HospitalController::class,'hospital']);
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return "cache is clear";
});

Route::get('run-cron', function() {
    \Artisan::call('schedule:run');
    echo "Cron Working";
});