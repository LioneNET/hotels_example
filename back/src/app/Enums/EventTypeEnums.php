<?php

namespace App\Enums;

use App\Enums\traits\EnumTrait;

enum EventTypeEnums: string
{
    use EnumTrait;

    const string LOCALIZATION = "event_type";

    case BOOKING_CREATED = "booking_created";
    case BOOKING_CANCELING = "booking_canceling";
    case GUEST_CHECKED_IN = "guest_checked_in";
    case USER_LOGGED_IN = "user_logged_in";
}
