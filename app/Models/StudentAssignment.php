<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StudentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignment_id',
        'score',
        'comments',
        'file_paths',
        'remarks',
    ];

    public function filePaths(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
        );
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
