<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateQuestionOptions;
use App\Models\ExamSection;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExamQuestionController extends Controller
{
    //
    public function edit($exam_id, $section_id)
    {
        $data['questions'] = $questions = Question::with('options')->where('exam_id', decode($exam_id))->where('section_id', decode($section_id))->get();

        if ($questions->count() == 0) {
            Session::flash('error_message', 'No Questions found for this section.');
            return redirect()->back();
        }
        //
        $data['section'] = ExamSection::with('exam')->where('id', decode($section_id))->first();
        return view('exams.questions.edit', $data);
    }

    public function update(Request $request)
    {
        dispatch(new UpdateQuestionOptions([
            'question_types' => $request->question_types,
            'option_types'   => $request->option_types,
            'question_id'    => decode($request->question_id),
        ]));
        Session::flash('success_message', 'Question types Updated');
        return redirect()->route('admin.exams.edit', ['id' => $request->exam_id]);
    }
    public function delete(Request $request)
    {
        $qid = $request->input('Qid');
        list($Qid, $Sid) = explode(",", $qid);
   
   
        $exam_id=Question::query()->select('exam_id','section_id')->where('id', $Qid)->first();
        Question::query()->where('id', $Qid)->delete();
       ExamSection::query()->where('id', $Sid)->update(['questions' => \DB::raw('questions - 1')]);
        Session::flash('success_message', 'Question delete successfully');
        //return redirect()->route('admin.exam_question.edit', ['exam_id' => encode($exam_id->exam_id),'section_id'=>encode($exam_id->section_id)]);
      //  $this->edit(encode($exam_id->exam_id),encode($exam_id->section_id));
      // return redirect()->back();
    }
}
