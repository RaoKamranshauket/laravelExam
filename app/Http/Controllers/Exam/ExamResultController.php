<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Services\UploadFile;
use App\Jobs\SendEmailTutor;
use App\Models\ExamFile;
use App\Models\ExamResult;
use App\Models\ExamResultDetail;
use App\Models\ExamSection;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Else_;

class ExamResultController extends Controller
{
    //
    public function view($exam_id,$student_id)
    {
        $data['exam'] =  DB::table('exam_results as er')
             ->leftJoin('exam_result_details as erd','er.id','=','erd.exam_result_id')
             ->join('exams as ex','er.exam_id','=','ex.id')
             ->join('questions as q','erd.question_id','=','q.id')
             ->join('options as op','erd.option_id','=','op.id')
             ->join('exam_sections as es','er.section_id','=','es.id')
             ->join('users as us','er.user_id','=','us.id')
             ->select(
                'ex.name',
                'es.section_name',
                'q.question',
                'op.option',
                'us.first_name','us.last_name',
             )
             ->where('ex.id',decode($exam_id))
             ->where('er.user_id',decode($student_id))
             ->orderBy('es.id','ASC')
             ->orderBy('q.id','ASC')
             ->get();
        return view('exams.view_result',$data);
    }
}
