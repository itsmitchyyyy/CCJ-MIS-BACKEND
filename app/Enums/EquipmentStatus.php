<?php

namespace App\Enums;

enum EquipmentStatus: string
{
    case Lost = 'lost';
    case Perfect = 'perfect';
    case Slight = 'slightly damaged';
    case Damage = 'damage';
    case Badly = 'badly damage';
}