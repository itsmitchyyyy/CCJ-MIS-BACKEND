<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;
use Laravel\Scout\Searchable;

class Subject extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'description',
        'code',
        'name',
        'units',
        'time_start',
        'time_end',
        'room',
        'days'
    ];

    public function days(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => implode('', explode(',', $value)),
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
