<?php

namespace App\Enums;

enum AccessType: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
}