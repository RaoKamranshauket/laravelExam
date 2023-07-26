<?php

namespace App\Jobs;

use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\User;
use App\Traits\UserAnswersReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendEmailTutorAndAdmins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UserAnswersReport;
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
        $exam_id = $this->details['exam_id'];
        $student_id = $this->details['student_id'];
        $exam = Exam::find($exam_id);
        $examTeacher = ExamStudent::with('teacher')->where('exam_id', $exam->id)->where('student_id', $student_id)->first();
        $link = route('admin.reporting.index');
        $link .= "?exam_id=" . encode($exam->id) . "&student_id=" . encode($student_id);
        if ($exam) {
            // Create File
            // Create File
            $reports = $this->getReport($exam->id, $student_id);

            $fileName = Str::random(10) . '-file.csv';
            $columnNames = [
                "Section Name",
                "Question",
                "Answer",
            ];

            $path = 'public/export/';

            $filename = 'public/export/' . $fileName;
            $dirname = dirname($filename);
            if (!is_dir($dirname)) {
                mkdir($dirname, 0755, true);
            }

            $file = fopen($path . $fileName, 'w');
            fputcsv($file, $columnNames, ',', chr(0));
            $sections = [];
            foreach ($reports as $report) {
                $section_name = $report['section_name'];
                $userAnswer = $report['user_answer'];
                $data = $section_name . "," . $report['question'] . " ," . $userAnswer;

                fputcsv($file, [$data], ',', chr(0));
            }

            fclose($file);
            // Get Teacher and admin ID
            $user = $examTeacher->teacher;
            $input['name'] = $user->first_name;
            $input['subject'] = "Student Submitted the exam " . $exam->name;
            $input['email'] = $user->email;
            $input['name'] = $user->first_name;
            $input['exam_name'] = $exam->name;
            $input['link'] = $link;
            $input['message'] = "A student has submitted the exam for the " . $exam->name;

            \Mail::send('mail.view_result', ['data' => $input], function ($message) use ($input, $fileName) {
                $message->to($input['email'], $input['name'])
                    ->subject($input['subject'])
                    ->attach('public/export/' . $fileName);
            });

            // send Email to Admins
            $admins = User::where('type', config('constants.USER_TYPE.ADMIN'))->limit(config('constants.ADMIN_EMAIL_LIMIT'))->get();
            $input = [];
            if ($admins) {
                foreach ($admins as $user) {
                    $input['name'] = $user->first_name;
                    $input['subject'] = "Student Submitted the exam " . $exam->name;
                    $input['email'] = $user->email;
                    $input['name'] = $user->first_name;
                    $input['exam_name'] = $exam->name;
                    $input['link'] = $link;
                    $input['message'] = "A student has submitted the result for the " . $exam->name;

                    \Mail::send('mail.view_result', ['data' => $input], function ($message) use ($input, $fileName) {
                        $message->to($input['email'], $input['name'])
                            ->subject($input['subject'])
                            ->attach('public/export/' . $fileName);
                    });
                }
            }
        }
    }
    private function findAnswer($report)
    {
        $answer = "";
        if ($report->question_type == config('constants.EXAM_QUESTION_TYPE.RADIO')) {
            $answer = $report->option;
        } else if ($report->question_type == config('constants.EXAM_QUESTION_TYPE.TEXT')) {
            $answer = $report->text_answer;
        }
        return $answer;
    }
}
