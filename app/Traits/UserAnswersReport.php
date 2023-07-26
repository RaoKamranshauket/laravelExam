<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait UserAnswersReport
{
    public function getReport($exam_id, $student_id)
    {
        $allQuestions = DB::table('questions as q')
            ->join('exam_sections as es', 'q.section_id', '=', 'es.id')
            ->where('q.exam_id', $exam_id)
            ->orderBy('q.id', 'ASC')
            ->select('q.id as question_id', 'q.question', 'es.id as section_id', 'es.section_name', 'q.question_type', 'q.option_type')
            ->get();
        $submittedQuestions = DB::table('exam_results as er')
            ->leftJoin('exam_result_details as erd', 'er.id', '=', 'erd.exam_result_id')
            ->join('exams as ex', 'er.exam_id', '=', 'ex.id')
            ->join('questions as q', 'erd.question_id', '=', 'q.id')
            ->leftJoin('options as op', 'erd.option_id', '=', 'op.id')
            ->join('exam_sections as es', 'er.section_id', '=', 'es.id')
            ->join('users as us', 'er.user_id', '=', 'us.id')
            ->join('questions as qs', 'erd.question_id', '=', 'qs.id')
            ->select(
                'ex.name',
                'es.section_name',
                'es.id as section_id',
                'q.question',
                'q.id as question_id',
                'op.option',
                'us.first_name',
                'us.last_name',
                'qs.question_type',
                'erd.text_answer',
            )
            ->where('ex.id', $exam_id)
            ->where('er.user_id', $student_id)
            ->orderBy('es.id', 'ASC')
            ->orderBy('q.id', 'ASC')
            ->get();
        $submittedQuestions = collect($submittedQuestions);
        $data = [];
        foreach ($allQuestions as $q) {
            $this_iteration = (array) $q;
            $userAnswerObject = $submittedQuestions->filter(function ($item, $key) use ($q) {
                return $item->question_id == $q->question_id && $item->section_id == $q->section_id && $item->question_type == $q->question_type;
            })->first();
            $this_iteration['user_answer'] = '';
            if ($userAnswerObject) {
                if ($userAnswerObject->question_type == config('constants.EXAM_QUESTION_TYPE.RADIO')) {
                    $this_iteration['user_answer'] = isset($userAnswerObject->option) && !empty($userAnswerObject->option) ? $userAnswerObject->option : '';
                } else if ($userAnswerObject->question_type == config('constants.EXAM_QUESTION_TYPE.TEXT')) {
                    $this_iteration['user_answer'] = isset($userAnswerObject->text_answer) && !empty($userAnswerObject->text_answer) ? $userAnswerObject->text_answer : '';
                }
            }
            $data[] = $this_iteration;
        }
        return $data;
    }
}
