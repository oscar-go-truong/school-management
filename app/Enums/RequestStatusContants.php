<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RequestStatusContants extends Enum
{
    const PENDING = 1;
    const REJECTED = 2;
    const APPROVED = 3;
    const CANCELED = 4;
}
