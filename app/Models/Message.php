<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
