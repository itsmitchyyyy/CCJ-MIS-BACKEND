<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\FacilityType;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'description',
        'room_number',
        'status',
    ];

    protected $casts = [
        'type' => FacilityType::class,
    ];

    public function requests()
    {
        return $this->hasMany(RequestFacility::class);
    }
}
