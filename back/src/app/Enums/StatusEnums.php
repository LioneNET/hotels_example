<?php

namespace App\Enums;

use App\Enums\traits\EnumTrait;

enum StatusEnums: string
{
    use EnumTrait;

    const string LOCALIZATION = 'status_alias';

    case PENDING = "pending";
    case CONFIRMED = "confirmed";
    case CHECKED_IN = "checked_in";
    case CHECKED_OUT = "checked_out";
    case CANCELLED = "cancelled";
}
