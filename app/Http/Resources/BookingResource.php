<?php


// namespace App\Http\Resources;

// use Carbon\Carbon;
// use Illuminate\Http\Resources\Json\JsonResource;
// use App\Http\Resources\Lab\TestCollection;
// use App\Http\Resources\Lab\ProfileCollection;
// use App\Http\Resources\Lab\PackageCollection;
// class BookingResource extends JsonResource
// {
//     /**
//      * Transform the resource into an array.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
//      */
//     public function toArray($request)
//     {
//         if($this->test_type == 'package'){
//             $data = new PackageCollection($this->packages);
//         }elseif($this->test_type == 'profile' ){
//             $data = new ProfileCollection($this->packages);
//         }else{
//             $data = new TestCollection($this->packages);
//         }

//         return [
//             "id"                => $this->id,
//             'hospital'          => new HospitalResource($this->hospital),
//             'test_type'           => $this->test_type,
//             'packages'          => $data ?? [],
//             'patient_id'        => $this->patient_id,
//             'member'            => $this->member,
//             'patient_address'   => $this->address,
//             'report_hard_copy'  => $this->report_hard_copy ? true : false,
//             'payment_mode'      => $this->payment_mode,
//             'transaction_status'=> $this->transaction_status,
//             'booking_amount'    => number_format($this->booking_amount ?? 0,2),
//             'status'            => $this->status,
//             'date'              => $this->date,
//             'time'              => $this->time,
//             'prescription_file' => !empty($this->prescription_file)  ? url('uploads/booking',$this->prescription_file) : null,
//             'report_image' => !empty($this->report_image)  ? url('uploads/appointment',$this->report_image) : null,
//             'invoice' => !empty($this->invoice)  ? url($this->invoice) : null,
//         ];
//     }
// }

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Lab\TestCollection;
use App\Http\Resources\Lab\ProfileCollection;
use App\Http\Resources\Lab\PackageCollection;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->test_type == 'package') {
            $data = new PackageCollection($this->packages);
        } elseif ($this->test_type == 'profile') {
            $data = new ProfileCollection($this->packages);
        } else {
            $data = new TestCollection($this->packages);
        }

        return [
            "id"                => $this->id,
            'hospital'          => new HospitalResource($this->hospital),
            'test_type'         => $this->test_type,
            'packages'          => $data ?? [],
            'patient_id'        => $this->patient_id,
            'member'            => $this->member,
            'patient_address'   => $this->address,
            'report_hard_copy'  => $this->report_hard_copy ? true : false,
            'payment_mode'      => $this->payment_mode,
            'transaction_status'=> $this->transaction_status,
            'booking_amount'    => number_format($this->booking_amount ?? 0, 2),
            'discounted_amount' => number_format($this->discounted_amount ?? 0, 2),
            'offer'             => new OfferResource($this->offer),
            'status'            => $this->status,
            'date'              => $this->date,
            'time'              => $this->time,
            'prescription_file' => !empty($this->prescription_file) ? url('uploads/booking', $this->prescription_file) : null,
            'report_image'      => !empty($this->report_image) ? url('uploads/appointment', $this->report_image) : null,
            'invoice'           => !empty($this->invoice) ? url($this->invoice) : null,
        ];
    }
}
