<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $main_invoice;
    public $details;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$main_invoice,$invoice_details)
    {
        $this->user = $user;
        $this->main_invoice = $main_invoice;
        $this->details = $invoice_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = 'Coffee & Tea';
        $subject = 'New Order';

        $setting = Setting::first();

        return $this->subject($subject)->from('info@coffeetea.ae', $name)->
        markdown('emails.user_email',[
            'user' => $this->user,
            'main_invoice' => $this->main_invoice,
            'details' => $this->details
        ]);
       
    }

    
}
