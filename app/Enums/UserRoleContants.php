<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRoleContants extends Enum
{
    const ADMIN =   1;
    const TEACHER =   2;
    const STUDENT = 3;
}
