<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_id',
        'send_from_id',
        'subject',
        'message',
        'status',
        'type',
        'attachment',
        'read_at',
        'sent_at',
    ];

    public function attachment(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => isset($value) ? json_decode($value, true) : [],
            set: fn (array $value) => isset($value) ? json_encode($value) : null
        );
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function sendFrom()
    {
        return $this->belongsTo(User::class, 'send_from_id');
    }
}
