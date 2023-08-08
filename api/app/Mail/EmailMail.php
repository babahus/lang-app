<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $view;
    public $data;

    public function __construct($subject, $view, $data = [])
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }

    public function build(): EmailMail
    {
        return $this->subject($this->subject)
            ->view($this->view)
            ->with($this->data);
    }
}
