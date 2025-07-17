<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case AUTHOR = 'author';
    case STUDENT = 'student';
}
