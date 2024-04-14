<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SubjectStudent extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subject_id'];

    public function grade(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
            set: fn (array $value) => json_encode($value)
        );
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
