<?php

namespace App\Jobs\Exams;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamRegisterStudent implements ShouldQueue
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
        $student_id = $this->details['student_id'];
        $exam_id = $this->details['exam_id'];
        $exam = Exam::find($exam_id);
        $subject = 'Invitation for Online Mock Exam: ' . $exam->name;

        // Get Student
        $student = User::find($student_id);
        $input['name'] = $student->first_name;
        $input['subject'] = $subject;
        $input['email'] = $student->email;
        $input['exam_name'] = $exam->name;
        $input['link'] = route('exam.attend', ['exam_id' => encode($exam_id), 'user_id' => encode($student->id)]);
       $input['message'] = "<html>
<head>
    <style>
        h2 {
            color: #555555;
            font-size: 18px;
        }

        ul {
            list-style-type: disc;
            color: #333333;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <p>Hi " . $student->first_name . "! You have been registered for the online mock test " . $exam->name . ". We recommend using Firefox or Chrome and completing your test on a laptop rather than a tablet or mobile device; other browsers and platforms may be less stable.</p>
    <h2>Test Tips:</h2>
    <ul>
        <li>You will not be able to restart the test once you begin, so make sure you have put aside enough time to take your exam in one sitting. Only the breaks on the real test administration will be provided.</li>
        <li>Have scratch paper on hand for notes and calculations.</li>
        <li>If you are taking a test that allows calculator usage, have your calculator ready.</li>
        <li>While taking the test, refrain from using the forward and backward buttons in your browser - doing so could cause your answers to be deleted.</li>
        <li>Once you answer a multiple-choice question, you may change your choice by double-clicking the same bubble.</li>
    </ul>
</body>
</html>";
        $input['btn_text'] = 'Attend Online Exam';
        if($student->cc_mails==null){
            \Mail::send('mail.exam_email_student', ['data' => $input], function ($message) use ($input, $student) {
                $message->to($input['email'], $input['name'])
                    ->subject($input['subject']);
            });
        }else{
            \Mail::send('mail.exam_email_student', ['data' => $input], function ($message) use ($input, $student) {
                $message->to($input['email'], $input['name'])->cc(explode(';', $student->cc_mails))
                    ->subject($input['subject']);
            });
        }
    }
}
