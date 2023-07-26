<?php

namespace App\Jobs;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mail;
use Illuminate\Support\Str;

class SendEmailTutor implements ShouldQueue
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
        $exam_id  = $this->details['exam_id'];
        $exam = Exam::with('teacher')->find($exam_id);
        if($exam){
             // Create File
              // Create File 
              $reports = DB::table('exams as ex')
              ->join('exam_results as er','ex.id','=','er.exam_id')
              ->leftJoin('exam_result_details as erd','er.id','=','erd.exam_result_id')
              ->join('questions as q','erd.question_id','=','q.id')
              ->join('options as op','erd.option_id','=','op.id')
              ->join('exam_sections as es','er.section_id','=','es.id')
              ->select(
                 'ex.name',
                 'es.section_name',
                 'q.question',
                 'op.option',
              )
              ->where('ex.id',$exam->id)
              ->orderBy('es.id','ASC')
              ->orderBy('q.id','ASC')
              ->get();
              $fileName = Str::random(10).'-file.csv';
            //   $columnNames = [
            //       'ID',
            //   ];
            $path = 'public/export/';
            
            $filename = 'public/export/'.$fileName;
            $dirname = dirname($filename);
            if (!is_dir($dirname))
            {
                mkdir($dirname, 0755, true);
            }
              
              $file = fopen($path . $fileName, 'w');

             // fputcsv($file, $columnNames);
              $sections  = [];
              foreach ($reports as $report) {
                  $section_name = $report->section_name;
                  if(!in_array($section_name,$sections)){
                     $sections[] = $section_name;
                     $data = $section_name;
                     $data = str_replace('"', '', $data);
                     
                     Log::info($data);
                  }else{
                        // Question and answer 
                        $data = $report->question." ,".$report->option;
                        $data = str_replace('"', '', $data);
                  }
                  fputcsv($file, [
                       $data,
                  ]);
              }
              
              fclose($file); 
             // Get Teacher ID 
            $user = $exam->teacher;
            $input['name'] = $user->name;
            $input['subject'] = "Student Submitted the exam ".$exam->name;
            $input['email'] = $user->email;
            $input['name'] = $user->name;
            $input['exam_name'] = $exam->name;
            $input['link'] = route('admin.exam_results.view', ['id' => encode($exam_id)]);
            $input['message'] = "A student has submitted the result for the ".$exam->name;
        
            \Mail::send('mail.view_result', ['data' => $input], function ($message) use ($input,$fileName) {
                $message->to($input['email'], $input['name'])
                        ->subject($input['subject'])
                        ->attach('public/export/' . $fileName);
            });

        }
      
    }
}
