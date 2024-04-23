<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'posted_by_id',
        'posted_at',
        'images',
        'status',
    ];

    public function images(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => isset($value) ? json_decode($value, true) : [],
            set: fn (array $value) => isset($value) ? json_encode($value) : null
        );
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by_id');
    }
}
