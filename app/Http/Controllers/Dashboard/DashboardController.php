<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    //
    public function index()
    {
       // $users  = DB::select('SELECT 100 * COUNT(DISTINCT users.id) / COUNT(DISTINCT users.id) AS active_percentage FROM users WHERE is_active');
       $data['users_count'] = User::count();
       $data['exams_count'] = Exam::count();
       $data['exam_percentage'] = DB::select("SELECT 100 * COUNT(DISTINCT exams.id) / COUNT(DISTINCT exams.id) AS completed_percentage
       FROM exams
       LEFT JOIN exam_results ON exams.id = exam_results.exam_id
       WHERE exam_results.status = 1");
       
       return view('dashboard.index',$data);
    }
    public function logout()
    {
        Auth::logout();
        Session::flash('error_message','You have been logged out!');
        return Redirect::to('/');
    }
}
