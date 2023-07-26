<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function options()
    {
        return $this->hasMany(Option::class,'question_id','id');
    }
    public function active_options()
    {
        return $this->hasMany(Option::class,'question_id','id')->where('is_active',1);
    }

}
