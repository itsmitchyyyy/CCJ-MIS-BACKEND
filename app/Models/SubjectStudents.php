<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;

class SubjectStudents extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subject_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}