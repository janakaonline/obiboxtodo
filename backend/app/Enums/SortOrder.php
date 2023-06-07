<?php

namespace App\Enums;

enum SortOrder: string{
    case None = '';
    case Assending = 'asc';
    case Desending = 'desc';
}
