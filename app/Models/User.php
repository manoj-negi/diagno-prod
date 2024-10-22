<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Doctorcategory;
use App\Models\DoctorAvailability;
use App\Models\Review;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     */
    public function hasRole($role)
        {
            return $this->role === $role;
        }
    protected $table = 'users';
    // protected $primarykey='id';
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'postal_code',
        'gst',
        'password',
        'number',
        'home_collection',
        'address',
        'dob',
        'age',
        'sex',
        'status',
        'hospital_logo',
        'is_online',
        'is_virtual',
        'is_approved',
        'doctor_bio',
        'experience',
        'address',
        'otp',
        'otp_expire',
        'exequatur_number',
        'is_doctor',
        'is_patient',
        'is_profile',
        'types_of_consultation',
        'preset_question_status',
        'consultation_symptoms',
        'city_id',
        'state_id',
        'is_hospital',
        'hospital_category',
        'wallet',
        'hospital_description',
        'refer_code',
        'profile_image',

    ];
    // protected $appends = ['doctor_today_appointments'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Roles Relation

    // public function getDoctorTodayAppointmentsAttribute()
    // {
    //     $data = Appointment::where('doctor_id',$this->id)->get();
    //     return $data;
    // }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function speciality()
    {
        return $this->belongsToMany(Speciality::class, 'doctor_speciality', 'doctor_id', 'speciality_id');
    }


    public function educations()
    {
        return $this->belongsToMany(Education::class, 'doctor_education', 'doctor_id', 'education_id');
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'doctor_hospital', 'doctor_id', 'hospital_id');
    }

    public function labnames()
    {
        return $this->hasMany(LabTest::class,'hospital_id','id');
    }
    public function labAvailability()
    {
        return $this->hasMany(LabsAvailability::class,'lab_id','id');
    }
    public function cities()
    {
        return $this->belongsToMany(City::class, 'lab_cities', 'lab_id', 'city');
    }

    public function availability()
    {
        return $this->hasMany(Avaliablity::class, 'doctor_id', 'id');
    }
    public function ratingsReviews()
    {
        return $this->hasMany(RatingReview::class, 'hospital_id', 'id');
    }
    public function getProfileImageAttribute($value)
    {
        return $value ?? 'user.png';
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'id');
    }
    public static function get($id)
    {

        $rating = RatingReview::selectRaw('AVG(ratings) as doctorRating')->where('doctor_id', $id)->first();
        return $rating->doctorRating;
    }
    // public static function AVGRating($id)
    // {
    //     $rating = RatingReview::selectRaw('AVG(ratings) as labRating')->where('hospital_id', $id)->first();
    //     return $rating->labRating;
    // }
    public static function DoctorRatings($id)
    {

        $rating = RatingReview::selectRaw('AVG(ratings) as doctorRating')->where('doctor_id', $id)->first();
        return $rating->doctorRating ? $rating->doctorRating : 0;
    }

    public function ratingReview()
    {
        return $this->hasMany(RatingReview::class, 'doctor_id', 'id');
    }

    public function subscription()
    {
         return $this->hasOne(SubscriptionInventory::class, 'id', 'subscription_inventory_id');

    }
    public function consult()
    {
        return $this->hasMany(ConsultDetail::class, 'doctor_id', 'id')->where('status', 1);
    }

    public function questions()
    {
        return $this->hasMany(questions::class, 'doctor_id', 'id')->where('status', 1);
    }
    public function report()
    {
        return $this->hasMany(AppointmentReport::class, 'patient_id');
    }
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'user_id', 'id');
    }
    public function typeOfConsultations()
    {
        return $this->hasMany(TypeOfConsultation::class, 'doctor_id', 'id ')->where('status', 1);
    }
     public function consultaionSymptom()
    {
        return $this->hasMany(ConsultaionSymptom::class, 'doctor_id', 'id');
    }
     public function hospitalCategory()
    {   
        return $this->hasOne(HospitalCategory::class, 'id', 'hospital_category');
    }
     public function City()
    {   
        return $this->hasOne(City::class, 'id', 'city_id');
    }
    public function state()
    {   
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    public function patient()
    {
        return $this->hasMany(PatientReport::class, 'patient_id', 'id');
    }
    
    public static function getNameById($id) {
        return static::where('id', $id)->first('name');
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'id', 'hospital_id');
    }

    public function hospitalDoctors()
    {
        return $this->hasMany(HospitalDoctor::class, 'hospital_id', 'id');
    }

    public function referearnning()
    {
        return $this->hasMany(AppointmentReferEaring::class, 'refer_code', 'refer_code');
    }


public function lab()
{
    return $this->belongsTo(Lab::class, 'lab_id');
}

    //use mutator
    public function getNameattribute($value)
    {
        return ucfirst($value);
    }

    public function getlastNameattribute($value)
    {
        return ucfirst($value);
    }
    public function getReferCodeattribute($value)
    {
        return ucfirst($value);
    }
}