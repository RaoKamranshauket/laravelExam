<?php

namespace App\Models;

use App\Casts\Encode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $casts = [
    //     'id' => Encode::class,
    // ];

    public function sections()
    {
        return $this->hasMany(ExamSection::class, 'exam_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function exam_result()
    {
        return $this->hasOne(ExamResult::class, 'exam_id', 'id');
    }
}
