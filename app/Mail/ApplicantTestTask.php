<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicantTestTask extends Mailable
{
    use Queueable, SerializesModels;

    public $uniqueKey;

    /**
     * Create a new message instance.
     *
     * @param $uniqueKey
     * @return void
     */
    public function __construct($uniqueKey)
    {
        $this->uniqueKey = $uniqueKey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('a.galavan@mail.ru')
                    ->markdown('emails.test-task');
    }
}
