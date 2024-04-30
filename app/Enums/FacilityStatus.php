<?php

namespace App\Enums;

enum FacilityStatus: string
{
    case Booked = 'booked';
    case Available = 'available';
    case Unavailable = 'unavailable';
}