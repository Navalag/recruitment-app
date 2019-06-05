<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicantTestTask extends Mailable
{
    use Queueable, SerializesModels;

    public $startTestLink;
    public $finishTestLink;

    /**
     * Create a new message instance.
     *
     * @param $startTestLink
     * @param $finishTestLink
     * @return void
     */
    public function __construct($startTestLink, $finishTestLink)
    {
        $this->startTestLink = $startTestLink;
        $this->$finishTestLink = $finishTestLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('example@example.com')
                    ->markdown('emails.test-task');
    }
}
