<?php

namespace App\Jobs;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateExamQuestions implements ShouldQueue
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
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exam_id = $this->details['exam_id'];
        $section_id = $this->details['section_id'];
        $total_questions = $this->details['total_questions'];
        $free_text = $this->details['free_text'];
        $options = $this->details['options'];
        $pattern = $this->details['pattren'];

        $trq = $total_questions - $free_text; // total radio button
        $opt = str_split($options[0]); // options as array of characters

        for ($i = 1; $i <= $total_questions; $i++) {
            if ($i <= $trq) {
                $questionType = 1;
                $optionType = ($pattern == 1) ? $options[0] : ($i % 2 == 0 ? $options[1] : $options[0]);
            } else {
                $questionType = 2;
                $optionType = null;
            }

            $question = Question::create([
                'exam_id' => $exam_id,
                'section_id' => $section_id,
                'question_type' => $questionType,
                'question' => $i,
                'option_type' => $optionType,
            ]);

            if ($questionType == 1 && $pattern == 1) {
                foreach ($opt as $key => $val) {
                    Option::create([
                        'question_id' => $question->id,
                        'option' => $val,
                    ]);
                }
            } elseif ($questionType == 1) {
                $optionArray = $i % 2 == 0 ? $options[1] : $options[0];
                foreach (str_split($optionArray) as $key => $val) {
                    Option::create([
                        'question_id' => $question->id,
                        'option' => $val,
                    ]);
                }
            }
        }
    }
}
