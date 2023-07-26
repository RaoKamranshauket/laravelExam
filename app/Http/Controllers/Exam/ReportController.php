<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\User;
use App\Traits\UserAnswersReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ReportController extends Controller
{
    //
    use UserAnswersReport;
    public function index(Request $request)
    {
        if (isset($request->exam_id) && isset($request->student_id)) {
            $data['result'] = $this->getReport(decode($request->exam_id), decode($request->student_id));
        }
        if(Auth::user()->type==2) {
            $data['exams'] =DB::table('exam_students')
                ->join('exams','exam_students.exam_id','exams.id')
                ->where('exam_students.teacher_id',Auth::user()->id)
                ->groupBy("exams.id")->get();
            $data['student'] = User::find(decode($request->student_id));
        }elseif(Auth::user()->type==3) {
            $data['exams'] =DB::table('exam_students')->join('exams','exam_students.exam_id','exams.id')
                ->where('student_id',Auth::user()->id)
                ->groupBy("exams.id")->get();
        }else{
            $data['exams'] = Exam::orderBy('name', 'ASC')->get();
            $data['student'] = User::find(decode($request->student_id));
        }
        $data['exam'] = Exam::find(decode($request->exam_id));

        return view('reporting.index', $data);
    }
    public function getStudents(Request $request)
    {
        if(Auth::user()->type==3){
            $students  = ExamStudent::with('student')
                ->where('exam_id', decode($request->exam_id))
                ->where('student_id',Auth::user()->id)->get();
        }elseif(Auth::user()->type==2){
            $students  = ExamStudent::with('student')
                ->where('exam_id', decode($request->exam_id))
                ->where('teacher_id',Auth::user()->id)->get();
        }else {
            $students = ExamStudent::with('student')->where('exam_id', decode($request->exam_id))->get();
        }
        $data  = [];
        foreach ($students as $s) {
            $data[] = [
                'id' => encode($s->student->id),
                'name' => $s->student->first_name . " " . $s->student->last_name
            ];
        }
        return response()->json(['success' => true, 'students' => $data]);
    }
}
