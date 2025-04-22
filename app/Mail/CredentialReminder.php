<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredentialReminder extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $newPassword;
    
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }
    
    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Informasi Login Anda')
            ->view('credentials');
    }
}
