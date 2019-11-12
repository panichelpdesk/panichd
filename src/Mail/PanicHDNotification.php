<?php

namespace PanicHD\PanicHD\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PanicHDNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $template;
    private $data;
    private $email_from;
    private $email_replyto;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template, $data, $email_from, $email_replyto, $subject)
    {
        $this->template = $template;
        $this->data = $data;
        $this->email_from = $email_from;
        $this->email_replyto = $email_replyto;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->from($this->email_from->email, $this->email_from->email_name)
            ->replyTo($this->email_replyto->email, $this->email_replyto->email_name)
            ->view($this->template)
            ->with($this->data);
    }
}
