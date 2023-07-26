<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function exam_result_details()
    {
        return $this->hasMany(ExamResultDetail::class,'exam_result_id','id');
    }
}
