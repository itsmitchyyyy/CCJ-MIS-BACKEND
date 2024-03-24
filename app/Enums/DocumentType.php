<?php

namespace App\Enums;

enum DocumentType: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}