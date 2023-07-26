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
use Illuminate\Support\Facades\Log;

class UpdateQuestionOptions implements ShouldQueue
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
        $question_types = $this->details['question_types'];
        $option_types = $this->details['option_types'];
        foreach($question_types as $question_id => $type){
            $question = Question::find(decode($question_id));
            $question->question_type =  $type;
            if($question->question_type == config('constants.EXAM_QUESTION_TYPE.RADIO'))
            {
                // Update option type 
                $options = isset($option_types[$question_id]) ? $option_types[$question_id]:null;
                Log::info($options);
                if(null <> $options){
                      $question->option_type = $options;
                }
                /**
                 * Update options
                 */
                $existingOptions = Option::where('question_id',decode($question_id))->where('is_active',1)->get()->toArray();// XYZ

                $newOptions = str_split($options); // ABC
                if(count($newOptions) > count($existingOptions)){
                    Log::info('IF');
                    foreach($newOptions as $key => $op){
                        if(isset($existingOptions[$key])){
                              // Find Option And Save it 
                              $optionObject  = Option::find($existingOptions[$key]['id']);
                              $optionObject->option = $op;
                              $optionObject->save();
          
                        }else{
                              // Insert 
                              Option::create(['question_id' => decode($question_id),'option' => $op]);
                        }
                  }
                }else if(count($newOptions) < count($existingOptions)){
                    Log::info('else IF');
                     foreach($existingOptions as $key=> $op){
        
                           if(isset($newOptions[$key])){
                                   // Update 
                                   $optionObject = Option::find($op['id']);
                                   $optionObject->option = $newOptions[$key];
                                   $optionObject->save();
                           }else{
                                // Remove these Options or disabled them 
                                $optionObject = Option::find($op['id']);
                                $optionObject->is_active = 0;
                                $optionObject->save();
                           }
                     }
                }else if(count($newOptions) == count($existingOptions)){
                   
                      foreach($existingOptions as $key => $op){
                           if(isset($newOptions[$key])){
                              $optionObject = Option::find($op['id']);
                              $optionObject->option = $newOptions[$key];
                              $optionObject->save();
                           }
                      }
                }

                /**
                 * Update Options Ends
                 */

            }else{
                 $question->option_type = null;

                 //TODO:: disable all options 
            }
            $question->save();
        }
    }
}
