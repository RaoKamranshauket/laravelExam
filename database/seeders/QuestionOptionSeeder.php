<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 1; $i <= 1000; $i++) {
            $question = Question::updateOrCreate(['question' => $i], [
                'exam_id' => 1,
                'section_id' => 1,
                'question' => $i,
            ]);
            if ($question->wasRecentlyCreated) {
                $options = ['A', 'B', 'C', 'D'];
                foreach ($options as $option) {
                    Option::create([
                        'question_id' => $question->id,
                        'option' => $option
                    ]);
                }
            }
        }
    }
}
