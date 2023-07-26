<?php

namespace App\Jobs;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailRegisterUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        //
        $this->details  = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject =  "User Registration";
        $input['message'] = "Hi! ".$this->details['first_name']." You’ve been registered for the Competitive Edge Tutoring Testing Portal.
          You can log in with the email address [".$this->details['email']."] and the password [".$this->details['password']."]";
          $input['message'] .= "  new text";

            $input['name'] = $this->details['first_name'];
            $input['subject'] = $subject;
            $input['email'] = $this->details['email'];
            $input['link'] = 'https://portal.competitiveedgetutoring.com/';
            $input['btn_text'] = 'Login';

            \Mail::send('mail.exam_email', ['data' => $input], function ($message) use ($input) {
                $message->to($input['email'], $input['name'])
                    ->subject($input['subject']);
            });

    }
}
