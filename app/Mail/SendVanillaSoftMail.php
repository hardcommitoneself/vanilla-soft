<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVanillaSoftMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $data)
    {
        //
    }

    public function build(): static
    {
        return $this->subject(data_get($this->data, 'subject'))
                ->markdown('mail.vanilla-soft-template');
    }
}
