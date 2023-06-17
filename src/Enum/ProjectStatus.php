<?php
declare(strict_types=1);

namespace App\Enum;

enum ProjectStatus: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case FAILED = 'failed';
    case DONE = 'done';
}
