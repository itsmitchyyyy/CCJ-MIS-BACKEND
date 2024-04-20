<?php

namespace App\Enums;

enum EquipmentStatus: string
{
    case Perfect = 'perfect';
    case Slight = 'slightly damaged';
    case Damage = 'damage';
    case Badly = 'badly damage';
}