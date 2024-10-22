<?php

namespace App\Mail;

use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PackageCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $package;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.package_created')
                    ->subject('New Package Created')
                    ->with([
                        'packageName' => $this->package->package_name,
                        // 'labName' => $this->package->lab->name, // Assuming there is a relationship defined between Package and User (Lab)
                        'amount' => $this->package->amount,
                    ]);
    }
}
