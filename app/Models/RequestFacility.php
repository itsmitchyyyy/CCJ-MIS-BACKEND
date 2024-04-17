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
        'approved_date',
        'status',
    ];

    protected $casts = [
        'status' => FacilityType::class,
    ];
}
