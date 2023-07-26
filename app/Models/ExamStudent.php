<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStudent extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function student()
    {
        return $this->belongsTo(User::class,'student_id','id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class,'exam_id','id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id','id');
    }
}
