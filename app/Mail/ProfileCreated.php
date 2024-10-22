<?php

namespace App\Mail;

use App\Models\LabProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfileCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $labProfile;

    public function __construct(LabProfile $labProfile)
    {
        $this->labProfile = $labProfile;
    }

    public function build()
    {
        return $this->view('emails.profile_created')
                    ->with([
                        'profileName' => $this->labProfile->profile_name,
                        'amount' => $this->labProfile->amount,
                        // Add more fields as needed
                    ]);
    }
}
