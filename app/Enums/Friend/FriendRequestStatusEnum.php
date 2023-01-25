<?php

namespace App\Enums\Friend;

use BenSampo\Enum\Enum;

final class FriendRequestStatusEnum extends Enum
{
    public const WAITING = 0;
    public const DECLINED = 1;
    public const APPROVED = 2;
}
