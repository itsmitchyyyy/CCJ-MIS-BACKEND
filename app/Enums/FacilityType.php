<?php

namespace App\Enums;

enum FacilityType: string
{
    case Regular = 'regular';
    case AVR = 'avr';
    case LAB = 'laboratories';
    case Equipment = 'equipment';
}