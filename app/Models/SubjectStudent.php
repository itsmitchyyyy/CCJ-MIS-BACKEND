<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Scout\Searchable;

class SubjectStudent extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['user_id', 'subject_id'];

    public function grade(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
            set: fn (array $value) => json_encode($value)
        );
    }

    public function toSearchableArray(): array
    {
        $this->loadMissing('user');

        return [
            'subjects.name' => '',
            'users.first_name' => '',
            'users.last_name' => ''
        ];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
