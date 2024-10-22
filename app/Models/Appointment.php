<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppointmentReport;
use App\Models\Offer;
class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';
    protected $primarykey = 'id';
    protected $fillable = [
        'date',
        'time',
        'status',
        'doctor_id',
        'patient_id',
        'refer_code',
        'doctor_consultation_type_id',
        'hospital_id',
        'token',
        'is_consult_completed',
        'test_type',
        'report_image',
        'report_title',
        'prescription_file',
        'member_id',
        'patient_address',
        'report_hard_copy',
        'payment_mode',
        'transaction_status',
        'transaction_id',
        'booking_amount',
        'discounted_amount',
        'offer_id',
        'invoice',
        'status'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'appointment_packages', 'appointment_id', 'package_id');
    }

    public function test()
    {
        return $this->hasOne(LabTest::class, 'id', 'test_id');
    }

    public function member()
    {
        return $this->hasOne(PatientFamily::class, 'id', 'member_id');
    }

    public function address()
    {
        return $this->hasOne(PatientsAddess::class, 'id', 'patient_address');
    }

    public function getIsActiveAttribute()
    {
        return $this->attributes['is_active'] == 1;
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = $value ? 1 : 0;
    }

    public function doctor()
    {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function hospital()
    {
        return $this->hasOne(User::class, 'id', 'hospital_id');
    }

    public function consultType()
    {
        return $this->hasOne(TypeOfConsultation::class, 'id', 'doctor_consultation_type_id');
    }

    public function consultDetail()
    {
        return $this->hasOne(ConsultDetail::class, 'appointment_id', 'id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'offer_id');
    }

    public function applyOffer($offerId)
    {
        $offer = Offer::find($offerId);

        if (!$offer) {
            throw new \Exception('Offer not found.');
        }

        $this->offer_id = $offerId;

        // Calculate the discounted amount
        $maxDiscount = $offer->maximum_discount; // Ensure 'maximum_discount' field exists in 'offers' table
        $this->discounted_amount = $this->booking_amount - $maxDiscount;

        // Save the appointment with the updated offer and discounted amount
        $this->save();

        return [
            'discounted_amount' => $this->discounted_amount,
            'offer' => $offer
        ];

}
}