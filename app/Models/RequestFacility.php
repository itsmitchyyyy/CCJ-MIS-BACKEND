<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\RequestFacilityStatus;

class RequestFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'user_id',
        'approved_by',
        'reservation_date',
        'reservation_time',
        'approved_date',
        'borrowed_date',
        'returned_date',
        'reason',
        'status',
        'rejected_reason',
    ];

    protected $casts = [
        'status' => RequestFacilityStatus::class,
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
