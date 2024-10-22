<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LabTestCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $labTest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($labTest)
    {
        $this->labTest = $labTest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.labTestCreated')
                    ->with([
                        'labTestName' => $this->labTest->test_name,
                        'amount' => $this->labTest->amount,
                    ]);
    }
}
