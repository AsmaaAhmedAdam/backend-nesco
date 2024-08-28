<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class SendImageMail extends Mailable
{
    use Queueable, SerializesModels;

   protected $data;
    protected $image;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param UploadedFile|null $image
     */
    public function __construct(array $data, UploadedFile $image = null)
    {
        $this->data = $data;
        $this->image = $image;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('emails.corporatelogin', ['data' => $this->data])
                     ->subject('Contact Form Mail!');


        if ($this->image) {
            $mail->attach($this->image->getRealPath(), [
                'as' => $this->image->getClientOriginalName(),
                'mime' => $this->image->getMimeType(),
            ]);

            $mail->with([
                'image' => $this->image->getRealPath()
            ]);
        }

        return $mail;
    }
}