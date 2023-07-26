<?php

namespace App\Jobs\Exams;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamRegisterTutor implements ShouldQueue
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
        $teacher_id = $this->details['teacher_id'];
        $exam = Exam::find($exam_id);
        $student = User::find($student_id);
         $teacher = User::find($teacher_id);
        $exam_name = $exam->name;
        $subject = "Exam " . $exam->name . " Registered for " . $student->first_name;
                $input['message'] = "Hi ".$teacher->first_name."! Exam " . $exam->name . " has been registered for the student " . $student->first_name . " " . $student->last_name;


        // Get Teacher
       
        $input['name'] = $teacher->first_name;
        $input['subject'] = $subject;
        $input['email'] = $teacher->email;
        $input['exam_name'] = $exam_name;
        $input['link'] = route('admin.exams.index');
        $input['btn_text'] = 'View Exams';

        \Mail::send('mail.exam_email_tutor', ['data' => $input], function ($message) use ($input) {
            $message->to($input['email'], $input['name'])
                ->subject($input['subject']);
        });
    }
}
