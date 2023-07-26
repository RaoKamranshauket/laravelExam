<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendQueueEmail implements ShouldQueue
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
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $subject = $this->details['subject'];
        $exam_id = $this->details['exam_id'];
        $exam_name = $this->details['exam_name'];
        $teacher_id = $this->details['teacher_id'];
        $student_id = $this->details['student_id'];
        // Get Teacher ID
        $users = User::whereIn('id', [$teacher_id, $student_id])->get();
        foreach ($users as $user) {
            $input['name'] = $user->name;
            $input['subject'] = $subject;
            $input['email'] = $user->email;
            $input['name'] = $user->name;
            $input['exam_name'] = $exam_name;
            $input['link'] = route('exam.attend', ['exam_id' => encode($exam_id), 'user_id' => encode($user->id)]);
            $input['message'] = "We invited to take this exam.";
            if ($user->type == config('constants.USER_TYPE.TEACHER')) {
                $input['link'] = route('admin.dashboard'); // need to change teacher dashboard in future
                $input['message'] = "Admin assigned you the exam.You can view this exam details by loging in into your dashboard.";
                $input['subject'] = "Exam " . $exam_name . " Assigned to you!";
                \Mail::send('mail.exam_email_tutor', ['data' => $input], function ($message) use ($input) {
                    $message->to($input['email'], $input['name'])
                        ->subject($input['subject']);
                });
            } elseif ($user->type == config('constants.USER_TYPE.STUDENT')) {
                if($user->cc_mails==null){
                    \Mail::send('mail.exam_email_student', ['data' => $input], function ($message) use ($input, $user) {
                        $message->to($input['email'], $input['name'])
                            ->subject($input['subject']);
                    });
                }else {
                    \Mail::send('mail.exam_email_student', ['data' => $input], function ($message) use ($input, $user) {
                        $message->to($input['email'], $input['name'])->cc(explode(';', $user->cc_mails))
                            ->subject($input['subject']);
                    });
                }
            }
        }
    }
}
